
// the function draw_tree load gedcom file from API '/pedigree/getTree' 
// next, its parse data to transform gedcom file to accepted structure for d3-org-chart
function draw_tree() {

    if(chart != undefined){
        editChartStatus()
    }


    disable_tools_bar()

    // load gedcom file from api for the current user
    let pedigree_id = get_pedigree_id();
    const promise = fetch('/pedigree/getTree/'+pedigree_id)
        .then(r => {
            if(r.status == 404){
                throw new Error(`HTTP error! Status: ${r.status}`);
            }

            if (r.headers.get('content-type').includes("text/html")) {
                throw {
                    message: 'Empty response received',
                    status: "Empty",
                };
            }
            return r.arrayBuffer()
        })
        .then(Gedcom.readGedcom)
        .catch(error => {
            if (typeof error === "object") {
                if(error.status == "Empty"){
                    if(document.getElementById("add-first-person-container") != null){
                        document.getElementById("add-first-person-container").classList.remove('d-none');
                    }
                    enable_load_gedcom()
                    return false;
                }
            }
            enable_load_gedcom()
            show_toast('danger', 'error', error)
            return false;
        });

    promise.then(gedcom => {
        // transform readGedcom structure to a structure accepted from d3-org-chart
        if (!gedcom) return;

        const treeData = transformGedcom(gedcom);
        familyData = treeData;
        // load settings from "/pedigree/settings"
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        let pedigree_id = get_pedigree_id();
        $.ajax({
            url: "/pedigree/settings/"+pedigree_id,
            type: 'GET',
            dataType: 'json',
            success: function (data) {
                if (data.error == false) {

                    var node_height = 70;
                    if(data.settings.node_template == '1'){
                        node_height = 70
                    }
                    if(data.settings.node_template == '2'){
                        node_height = 180
                    }
                    if(data.settings.node_template == '3'){
                        node_height = 140
                    }
                    if(data.settings.node_template == '4'){
                        node_height = 130
                    }

                    var children_margin = 60;
                    if(data.settings.node_template == '3'){
                        children_margin = 150
                    }
                    if(data.settings.node_template == '4'){
                        children_margin = 180
                    }

                    var connection_stroke_width = 2
                    if(data.settings.node_template == '4'){
                        connection_stroke_width = 2
                    }

                    var node_width = 190
                    if(data.settings.node_template == '4'){
                        node_width = 115
                    }

                    var node_width_spouse = 400
                    if(data.settings.node_template == '4'){
                        node_width_spouse = 230
                    }

                    treeConfiguration = {
                        chartContainer: '#graph', // root svg
                        zoomLevel: data.settings.zoom_level,
                        // default portrait
                        maleIcon : "/storage/placeholder_portraits/"+data.settings.default_male_image+".jpg",
                        femaleIcon : "/storage/placeholder_portraits/"+data.settings.default_female_image+".jpg",
                        // product features
                        maxGenerations : data.settings.max_generation,
                        maxNodes : data.settings.max_nodes,
                        // tempale
                        nodeTemplate : data.settings.node_template,
                        bgTemplate : data.settings.bg_template,
                        // note settings
                        note_type : data.settings.note_type,
                        note_text_color : data.settings.note_text_color,

                        // photo settings
                        photos_type : data.settings.photos_type,
                        default_filter : data.settings.default_filter,
                        // height & width
                        nodeWidth: node_width,
                        nodeWidthSpouse: node_width_spouse,
                        get nodeWidthDiff() {
                            return this.nodeWidthSpouse - this.nodeWidth * 2;
                        },
                        get linkShift() {
                            return Math.round((this.nodeWidth + this.nodeWidthDiff) / 2) + 1;// for moving the link coming from parent
                        },
                        nodeHeight: node_height,
                        nodeHeightSpouse: node_height,

                        // margins
                        siblingsMargin: 23,
                        childrenMargin: children_margin,
                        neighbourMargin: 13,
                        rootMargin: 0,

                        // child & spouse lines
                        primarySpouseLinkStroke: data.settings.spouse_link_color,
                        linkStroke: data.settings.bio_child_link_color,
                        linkStrokeAdopted: data.settings.adop_child_link_color,
                        linkStrokeWidth: 2,
                        connectionStroke: data.settings.spouse_link_color,
                        connectionStrokeWidth: connection_stroke_width,

                        // expand collapse button
                        nodeButtonWidth: '',
                        nodeButtonHeight: '',
                        nodeButtonX: -20,
                        nodeButtonY: -10,

                        // node colors
                        boxColor: data.settings.box_color,
                        maleColor: data.settings.male_color,
                        femaleColor: data.settings.female_color,
                        bloodColor: data.settings.blood_color,
                        notbloodColor: data.settings.notblood_color,

                        // text color
                        textColor: data.settings.text_color,

                        // band color
                        bandColor: data.settings.band_color,

                    }
                    // after loading settings draw chart with treeData and treeConfiguration
                    renderChart();
                    if(document.getElementById("add-first-person-container") != null){
                        document.getElementById("add-first-person-container").remove();
                    }
                    

                } else {
                    show_toast('danger', 'error', data.error)
                }

            },
            error: function (xhr, status, error) {
                if ('responseJSON' in xhr) {
                    show_toast('danger', 'error', xhr.responseJSON.message)
                } else {
                    show_toast('danger', 'error', error)
                }

                return null;
            }
        });

    });

}


// test if variable is an object
function isObject(variable) {
    return typeof variable === 'object' && variable !== null;
}

// get status (living or Deceased) from individual object
function get_status(individual) {
    if (individual.getEventDeath().length == 0) {
        return 'Living'
    }
    else {
        return 'Deceased'
    }
}

// get death date from individual object
function get_death_date(individual) {

    if (individual.getEventDeath().length == 0) {
        return null
    }
    else {
        if (individual.getEventDeath().getDate().length == 0) {
            return null
        }
        else {
            return individual.getEventDeath().getDate()[0].value
        }
    }

}

// get birth date from individual object
function get_birth_date(individual) {
    if (individual.getEventBirth().length == 0) {
        return null
    }
    else {
        if (individual.getEventBirth().getDate().length == 0) {
            return null
        }
        else {
            return individual.getEventBirth().getDate()[0].value
        }
    }

}

// get name from individual object
function get_name(individual) {

    

    var name = individual.getName();
    
    if (name.length > 0) {

        

        let fullName = name[0].value;
        let separator = "/";
        let indexes = [...fullName.matchAll(new RegExp(separator, "g"))].map(match => match.index);

        // more than 2 '/'
        if(indexes.length != 2){
            return {
                'firstname': '',
                'lastname': '',
                'name':''
            }
        }

        let lastName = '';
        let firstName = '';

        // if there is no substring between 2 '/'
        lastName = fullName.slice(indexes[0]+1, indexes[1]);
        firstName = fullName.slice(0, indexes[0]);

        lastName = lastName.trim()
        firstName = firstName.trim()
        
        return {
            'firstname': firstName,
            'lastname': lastName,
            'name':firstName+' '+lastName
        }
    }
    else {
        return {
            'firstname': '',
            'lastname': '',
            'name':''
        }
    }
}

// get sex from individual object
function get_sex(individual) {
    var sex = individual.getSex();
    if (sex.length > 0) {
        return sex[0].value
    }
    else {
        return ''
    }
}

// check if person is foster
/*
function is_foster(individual, familyId) {


    foster = false
    fosterType = undefined

    familyFoster = individual.getFamilyAsChild()

    console.log(familyFoster[0])


    return { foster, fosterType }

}
*/

// check if the child is adopted
function is_adopted(individual, familyId) {


    adopted = false
    adoptedType = undefined

    adoption = individual.getEventAdoption()
    if (adoption.length == 0) {
        return { adopted, adoptedType }
    }

    familyAdoptive = adoption.getFamilyAsChildReference()
    if (familyAdoptive.length == 0) {
        return { adopted, adoptedType }
    }


    familyAdoptiveId = familyAdoptive[0].value
    if (familyAdoptiveId == familyId) {
        adopted = true
    }

    adoptedByWhom = familyAdoptive.getAdoptedByWhom()
    if (adoptedByWhom.length > 0) {
        adoptedType = adoptedByWhom[0].value
    }

    return { adopted, adoptedType }

}

// check if an individual exist
function personIdExists(people, id) {
    return Object.values(people).filter(person => person.personId === id);
}


// iterate over all families and get couples
function get_couples(families, individualRecords) {


    families.forEach((family, key, array) => {

        // check if family is an object
        if (!isObject(family[0])) {
            return;
        }

        // get husband and wife
        husband = family.getHusband().getIndividualRecord()
        wife = family.getWife().getIndividualRecord()

        // check wich is a parent husband or wife, 
        let parent = {}
        let spouse = {}

        /// the husband is the parent if it's a child in another family
        if (husband.getFamilyAsChild().length > 0) {
            parent = husband
            spouse = wife
            /// the wife is the parent if it's a child in another family
        } else if (wife.getFamilyAsChild().length > 0) {
            parent = wife
            spouse = husband
            /// if wife and husband not a child in another family, the default parent is the husband
        } else {
            parent = husband
            spouse = wife
        }

        // create a parent person from selected parent (husband or wife)
        let parentPerson = {}
        // family with parent
        if (parent.length > 0) {
            // family with parent and with spouse
            if (spouse.length > 0) {
                var parentPhoto = undefined;
                if (parent.getNote()[0] != undefined) {
                    parentPhoto = parent.getNote()[0].value;
                }
                
                var spousePhoto = undefined;
                if (spouse.getNote()[0] != undefined) {
                    spousePhoto = spouse.getNote()[0].value;
                }


                parentPerson = {
                    id: parent[0].pointer + '-' + spouse[0].pointer,
                    personId: parent[0].pointer,
                    firstName: get_name(parent).firstname,
                    lastName: get_name(parent).lastname,
                    name: get_name(parent).name,
                    gender: get_sex(parent),
                    status: get_status(parent),
                    birth: get_birth_date(parent),
                    death: get_death_date(parent),
                    photo: parentPhoto,
                    parentId: undefined,
                    personOrder: undefined,
                    childOrder: undefined,
                    spouseIds: [],
                    spouseId: spouse[0].pointer,
                    spouseFirstName: get_name(spouse).firstname,
                    spouseLastName: get_name(spouse).lastname,
                    spouseName: get_name(spouse).name,
                    spouseGender: get_sex(spouse),
                    spouseStatus: get_status(spouse),
                    spouseBirth: get_birth_date(spouse),
                    spouseDeath: get_death_date(spouse),
                    spousePhoto: spousePhoto,
                    spouseOrder: undefined,
                    spouseDrillTo: false,
                    primarySpouseId: undefined,
                    path: undefined,
                };

            }
            // family with parent and without spouse
            else {
                var photo = undefined;
                if (parent.getNote()[0] != undefined) {
                    photo = parent.getNote()[0].value;
                }
                parentPerson = {
                    id: parent[0].pointer,
                    personId: parent[0].pointer,
                    firstName: get_name(parent).firstname,
                    lastName: get_name(parent).lastname,
                    name: get_name(parent).name,
                    gender: get_sex(parent),
                    status: get_status(parent),
                    birth: get_birth_date(parent),
                    death: get_death_date(parent),
                    photo: photo,
                    parentId: undefined,
                    personOrder: undefined,
                    childOrder: undefined,
                    spouseIds: [],
                    spouseId: undefined,
                    spouseFirstName: undefined,
                    spouseLastName: undefined,
                    spouseName:undefined,
                    spouseGender: undefined,
                    spouseStatus: undefined,
                    spouseBirth: undefined,
                    spouseDeath: undefined,
                    spousePhoto: undefined,
                    spouseOrder: undefined,
                    spouseDrillTo: false,
                    primarySpouseId: undefined,
                    path: undefined,
                };

            }
        }
        // family without parent
        else {
            // family without parent and with spouse, so the spouse is the parent
            if (spouse.length > 0) {
                var photo = undefined;
                if (spouse.getNote()[0] != undefined) {
                    photo = spouse.getNote()[0].value;
                }
                parentPerson = {
                    id: spouse[0].pointer,
                    personId: spouse[0].pointer,
                    firstName: get_name(spouse).firstname,
                    lastName: get_name(spouse).lastname,
                    name: get_name(spouse).name,
                    gender: get_sex(spouse),
                    status: get_status(spouse),
                    birth: get_birth_date(spouse),
                    death: get_death_date(spouse),
                    photo: photo,
                    parentId: undefined,
                    personOrder: undefined,
                    childOrder: undefined,
                    spouseIds: [],
                    spouseId: undefined,
                    spouseFirstName: undefined,
                    spouseLastName: undefined,
                    spouseName: undefined,
                    spouseGender: undefined,
                    spouseOrder: undefined,
                    spouseDrillTo: false,
                    primarySpouseId: undefined,
                    spousePhoto: undefined,
                    path: undefined,
                };
            }
            // family without parent and without spouse
            else {
                return;
            }

        }

        // check if parent exist already in individualRecords update primarySpouseId with the id of the first match of parent
        var personExist = personIdExists(individualRecords, parentPerson.personId)
        if (personExist.length > 0) {
            // change primarySpouseId to link second and third spouse with the person
            parentPerson.primarySpouseId = personExist[0].id

            // get all spouse from spouseIds to update spouseIds for current person
            var spouseIds = personExist[0].spouseIds
            spouseIds.push(parentPerson.spouseId)
            parentPerson.spouseIds = spouseIds
        }
        else {
            // if person dont exist just add spouseId to spouseIds
            if (parentPerson.spouseId != undefined) {
                parentPerson.spouseIds.push(parentPerson.spouseId)
            }
        }



        // add parent to individualRecords
        individualRecords[parentPerson.id] = parentPerson;

    });
    // end foreach

}// end get_couples function


// get all children in families
function get_children(families, individualRecords) {


    families.forEach((family, key, array) => {

        // check if family is an object
        if (!isObject(family[0])) {
            return;
        }

        // get husband and wife
        husband = family.getHusband().getIndividualRecord()
        wife = family.getWife().getIndividualRecord()

        // check wich is a parent husband or wife, 
        let parent = {}
        let spouse = {}

        /// the husband is the parent if it's a child in another family
        if (husband.getFamilyAsChild().length > 0) {
            parent = husband
            spouse = wife
            /// the wife is the parent if it's a child in another family
        } else if (wife.getFamilyAsChild().length > 0) {
            parent = wife
            spouse = husband
            /// if wife and husband not a child in another family, the default parent is the husband
        } else {
            parent = husband
            spouse = wife
        }

        // create a parentId from selected parent (husband and wife)
        var parentId = undefined
        // family with parent
        if (parent.length > 0) {
            // family with parent and with spouse
            if (spouse.length > 0) {
                parentId = parent[0].pointer + '-' + spouse[0].pointer;
            }
            // family with parent and without spouse
            else {
                parentId = parent[0].pointer;
            }
        }
        // family without parent
        else {
            // family without parent and with spouse, so the spouse is the parent
            if (spouse.length > 0) {
                parentId = spouse[0].pointer;
            }
            // family without parent and without spouse
            else {
                return;
            }
        }


        // iterate over all children
        children = family.getChild().getIndividualRecord().arraySelect()

        children.forEach((child, key, array) => {

            /*
            if(child[0].pointer == "@I10@"){
                const { foster, fosterype } = is_foster(child, family[0].pointer)
            }
            */

            var photo = undefined;
            if (child.getNote()[0] != undefined) {
                photo = child.getNote()[0].value;
            }


            // check if child is adopted
            const { adopted, adoptedType } = is_adopted(child, family[0].pointer)

            // check if child is foster
            

            // check if child exist as couple
            childAsCouples = personIdExists(individualRecords, child[0].pointer)

            // if child exist as couple in individualRecords update parentId in all couples
            if (childAsCouples != false) {
                childAsCouples.forEach((childAsCouple, key, array) => {
                    individualRecords[childAsCouple.id].parentId = parentId
                    individualRecords[childAsCouple.id].adopted = adopted
                    individualRecords[childAsCouple.id].adoptedType = adoptedType
                })

            }
            // if child not exist as couple add it to individualRecords
            else {
               
                const childPerson = {
                    id: child[0].pointer,
                    personId: child[0].pointer,
                    firstName: get_name(child).firstname,
                    lastName: get_name(child).lastname,
                    name: get_name(child).name,
                    gender: get_sex(child),
                    status: get_status(child),
                    birth: get_birth_date(child),
                    death: get_death_date(child),
                    photo: photo,
                    parentId: parentId,
                    adopted: adopted,
                    adoptedType: adoptedType,
                    personOrder: undefined,
                    childOrder: undefined,
                    spouseIds: [],
                    spouseId: undefined,
                    spouseFirstName: undefined,
                    spouseLastName: undefined,
                    spouseName: undefined,
                    spouseGender: undefined,
                    spouseOrder: undefined,
                    spouseDrillTo: false,
                    primarySpouseId: undefined,
                    spousePhoto: undefined,
                    path: undefined,
                };
                individualRecords[childPerson.id] = childPerson
            }

        });// end children foreach

    });// end families foreach

}

// parse gedcom file 
function transformGedcom(gedcom) {

    let individualRecords = [];

    // get all families
    families = gedcom.getFamilyRecord().arraySelect()

    // if there is no families add just the first individual
    if (families.length == 0) {
        indi = families = gedcom.getIndividualRecord().arraySelect()[0]
        var photo = undefined;
        if (indi.getNote()[0] != undefined) {
            photo = indi.getNote()[0].value;
        }
        let indiPerson = {
            id: indi[0].pointer,
            personId: indi[0].pointer,
            firstName: get_name(indi).firstname,
            lastName: get_name(indi).lastname,
            name: get_name(indi).name,
            gender: get_sex(indi),
            status: get_status(indi),
            birth: get_birth_date(indi),
            death: get_death_date(indi),
            photo: photo,
            parentId: undefined,
            personOrder: undefined,
            childOrder: undefined,
            spouseIds: [],
            spouseId: undefined,
            spouseFirstName: undefined,
            spouseLastName: undefined,
            spouseName: undefined,
            spouseGender: undefined,
            spouseOrder: undefined,
            spouseDrillTo: false,
            primarySpouseId: undefined,
            spousePhoto: undefined,
            path: undefined,
        };

        individualRecords[indiPerson.id] = indiPerson;
    }
    else{
        // if families length > 0, iterate over all families and get couples
        get_couples(families, individualRecords)

        // if families length > 0, iterate over all families and get children
        get_children(families, individualRecords)
    }

    

    // add hidden route to individualRecords
    const hiddenRootNode = {
        id: "hidden_root",
        name: "Hidden Root",
        parentId: null
    };
    individualRecords[hiddenRootNode.id] = hiddenRootNode;
    
    // check if a person without parentId and assign the hidden_route as parent
    let data = Object.values(individualRecords)
    data.forEach(member => {
        if (member.parentId === undefined) {
            member.parentId = hiddenRootNode.id;
        }
    });

    // sort data by birth date to sort children from old to young

    data.sort((a, b) => {

        return parseDateToSort(a.birth) - parseDateToSort(b.birth)
    
    });

    /*
    data.forEach(person => {
        if(person.personId == '@I7@'){
            person.spouseIds = [ "@I12@", "@I10@" ]
            person.primarySpouseId = '@I7@-@I12@'
            if(person.id == '@I7@-@I12@'){
                person.primarySpouseId = undefined
            }
        }
    });

    let index1 = 5; // Object with id: 2
    let index2 = 6; // Object with id: 4

    // Swap the two objects
    [data[index1], data[index2]] = [data[index2], data[index1]];
    */
    // 48

    


    
    return (data)

}

function reorderItems(array, orderIds) {
    // Create a map for quick lookup of items by ID
    const itemMap = array.reduce((map, item) => {
      map[item.id] = item;
      return map;
    }, {});
  
    // Reorder based on orderIds
    const reorderedItems = orderIds.map(id => itemMap[id]);
  
    return array.map(item =>
      orderIds.includes(item.id) ? reorderedItems.shift() : item
    );
  }

function parseDateToSort(dateStr) {
    if (!dateStr) return new Date(0); // For undefined dates, return a very early date
    const parts = dateStr.split(' ');

    if (parts.length === 1) {
        // Only year is given
        return new Date(parts[0], 0, 1); // Use January 1st of that year
    } else {
        // Full date is given
        if(parts.length <= 3){
            const [day, month, year] = parts;
            const monthNames = ["JAN", "FEB", "MAR", "APR", "MAY", "JUN", "JUL", "AUG", "SEP", "OCT", "NOV", "DEC"];
            const monthIndex = monthNames.indexOf(month) + 1;
            return new Date(year, monthIndex, day);
        }
        else{
            
            const [about,day, month, year] = parts;
            const monthNames = ["JAN", "FEB", "MAR", "APR", "MAY", "JUN", "JUL", "AUG", "SEP", "OCT", "NOV", "DEC"];
            const monthIndex = monthNames.indexOf(month) + 1;
            return new Date(year, monthIndex, day);
        }
        
    }
}