treeData = [
    {
        id: 'hidden_root', // this is unique id for husband and wife
        personId: -1,
    }, {
        id: '1-2', // this is unique id for husband and wife
        personId: 1,
        label1: 'Father',
        label2: 'Father',
        name: 'Father',
        gender: "M",
        photo: "",
        parentId: "hidden_root", // root parent
        personOrder: '',
        childOrder: 1, // currently not used in UI
        size: 1, // probably not used
        spouseId: 2,
        spouseName: "Mother",
        spouseLabel1: "Mother",
        spouseGender: "F",
        spouseOrder: 1, // currently not used in UI
        spouseDrillTo: false, // this is required to show multiple trees
        primarySpouseId: undefined, // used in multiple spouses for the second spouse
        spousePhoto: undefined,
        path: undefined, // may not be used
    }
    ,
    {
        id: '3', // this is unique id for husband and wife
        personId: 3,
        label1: 'Son 0',
        label2: 'Son 0',
        name: 'Son 0',
        gender: "M",
        photo: "",
        parentId: '1-2', // root parent
        personOrder: '',
        childOrder: 1, // currently not used in UI
        size: 1, // probably not used
        spouseId: undefined,
        spouseName: undefined,
        spouseLabel1: undefined,
        spouseGender: undefined,
        spouseOrder: undefined, // currently not used in UI
        spouseDrillTo: false, // this is required to show multiple trees
        primarySpouseId: undefined, // used in multiple spouses for the second spouse
        spousePhoto: undefined,
        path: undefined, // may not be used
    }
    ,
    {
        id: '4-5', // this is unique id for husband and wife
        personId: 4,
        label1: 'Son',
        label2: 'Son',
        name: 'Son',
        gender: "M",
        photo: "",
        parentId: '1-2', // root parent
        personOrder: '',
        childOrder: 1, // currently not used in UI
        size: 1, // probably not used
        spouseId: 5,
        spouseName: "Wife - 1",
        spouseLabel1: "Wife - 1",
        spouseGender: "F",
        spouseOrder: 1, // currently not used in UI
        spouseDrillTo: false, // this is required to show multiple trees
        primarySpouseId: undefined, // used in multiple spouses for the second spouse
        spousePhoto: undefined,
        path: undefined, // may not be used
    }
    ,
    {
        id: '4-6', // this is unique id for husband and wife
        personId: 4,
        label1: 'Son',
        label2: 'Son',
        name: 'Son',
        gender: "M",
        photo: "",
        parentId: '1-2', // root parent
        personOrder: '',
        childOrder: 1, // currently not used in UI
        size: 1, // probably not used
        spouseId: 6,
        spouseName: "Wife - 2",
        spouseLabel1: "Wife - 2",
        spouseGender: "F",
        spouseOrder: 1, // currently not used in UI
        spouseDrillTo: false, // this is required to show multiple trees
        primarySpouseId: '4-5', // used in multiple spouses for the second spouse
        spousePhoto: undefined,
        path: undefined, // may not be used
    }
    ,
    {
        id: '4-7', // this is unique id for husband and wife
        personId: 4,
        label1: 'Son',
        label2: 'Son',
        name: 'Son',
        gender: "M",
        photo: "",
        parentId: '1-2', // root parent
        personOrder: '',
        childOrder: 1, // currently not used in UI
        size: 1, // probably not used
        spouseId: 7,
        spouseName: "Wife - 3",
        spouseLabel1: "Wife - 3",
        spouseGender: "F",
        spouseOrder: 1, // currently not used in UI
        spouseDrillTo: false, // this is required to show multiple trees
        primarySpouseId: '4-5', // used in multiple spouses for the second spouse
        spousePhoto: undefined,
        path: undefined, // may not be used
    }
    ,
    {
        id: '5', // this is unique id for husband and wife
        personId: 5,
        label1: 'Son 2',
        label2: 'Son 2',
        name: 'Son 2',
        gender: "M",
        photo: "",
        parentId: '4-5', // root parent
        personOrder: '',
        childOrder: 1, // currently not used in UI
        size: 1, // probably not used
        spouseId: undefined,
        spouseName: undefined,
        spouseLabel1: undefined,
        spouseGender: undefined,
        spouseOrder: undefined, // currently not used in UI
        spouseDrillTo: false, // this is required to show multiple trees
        primarySpouseId: undefined, // used in multiple spouses for the second spouse
        spousePhoto: undefined,
        path: undefined, // may not be used
    },
    {
        id: '6', // this is unique id for husband and wife
        personId: 6,
        label1: 'Son 3',
        label2: 'Son 3',
        name: 'Son 3',
        gender: "F",
        photo: "",
        parentId: '4-6', // root parent
        personOrder: '',
        childOrder: 1, // currently not used in UI
        size: 1, // probably not used
        spouseId: undefined,
        spouseName: undefined,
        spouseLabel1: undefined,
        spouseGender: undefined,
        spouseOrder: undefined, // currently not used in UI
        spouseDrillTo: false, // this is required to show multiple trees
        primarySpouseId: undefined, // used in multiple spouses for the second spouse
        spousePhoto: undefined,
        path: undefined, // may not be used
    }

];
// the function draw_tree load gedcom file from API '/pedigree/getTree' 
// next, its parse data to transform gedcom file to accepted structure for d3-org-chart
function draw_tree() {


    const promise = fetch('/pedigree/getTree')
        .then(r => r.arrayBuffer())
        .then(Gedcom.readGedcom);

    promise.then(gedcom => {


        const treeData = transformGedcom(gedcom);
        draw_graph(treeData)

    });

}


// test if variable is an object
function isObject(variable) {
    return typeof variable === 'object' && variable !== null;
}

// get death date from individual object
function get_death_date(individual) {
    if (individual.getEventDeath().length <= 0) {
        return null
    }
    if (individual.getEventDeath().getDate().length <= 0) {
        return null
    }

    return individual.getEventDeath().getDate()[0].value
}

// get birth date from individual object
function get_birth_date(individual) {
    if (individual.getEventBirth().length <= 0) {
        return null
    }
    if (individual.getEventBirth().getDate().length <= 0) {
        return null
    }

    return individual.getEventBirth().getDate()[0].value
}

// get name from individual object
function get_name(individual) {
    var name = individual.getName();
    if (name.length > 0) {
        return name[0].value.replace(/\//g, " ").trimEnd()
    }
    else {
        return ''
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

// check if the child is adopted
function is_adopted(individual,familyId){
    
    
    adopted = false
    adoptedType = undefined

    adoption = individual.getEventAdoption()
    if(adoption.length == 0){
        return { adopted, adoptedType }
    }

    familyAdoptive = adoption.getFamilyAsChildReference()
    if(familyAdoptive.length == 0){
        return { adopted, adoptedType }
    }

    
    familyAdoptiveId = familyAdoptive[0].value
    if(familyAdoptiveId == familyId){
        adopted = true
    }

    adoptedByWhom = familyAdoptive.getAdoptedByWhom()
    if(adoptedByWhom.length > 0){
        adoptedType = adoptedByWhom[0].value
    }

    return { adopted, adoptedType }

}

// check if an individual exist
function personIdExists(people, id) {
    return Object.values(people).filter(person => person.personId === id);
}


// iterate over all families and get couples
function get_couples(families,individualRecords) {


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
                parentPerson = {
                    id: parent[0].pointer + '-' + spouse[0].pointer,
                    personId: parent[0].pointer,
                    label1: get_name(parent),
                    label2: get_name(parent),
                    name: get_name(parent),
                    gender: get_sex(parent),
                    birth: get_birth_date(parent),
                    death: get_death_date(parent),
                    photo: undefined,
                    parentId: undefined,
                    personOrder: undefined,
                    childOrder: undefined,
                    spouseId: spouse[0].pointer,
                    spouseName: get_name(spouse),
                    spouseLabel1: get_name(spouse),
                    spouseGender: get_sex(spouse),
                    spouseOrder: undefined,
                    spouseDrillTo: false,
                    primarySpouseId: undefined,
                    spousePhoto: undefined,
                    path: undefined,
                };

            }
            // family with parent and without spouse
            else {
                parentPerson = {
                    id: parent[0].pointer,
                    personId: parent[0].pointer,
                    label1: get_name(parent),
                    label2: get_name(parent),
                    name: get_name(parent),
                    gender: get_sex(parent),
                    birth: get_birth_date(parent),
                    death: get_death_date(parent),
                    photo: undefined,
                    parentId: undefined,
                    personOrder: undefined,
                    childOrder: undefined,
                    spouseId: undefined,
                    spouseName: undefined,
                    spouseLabel1: undefined,
                    spouseGender: undefined,
                    spouseOrder: undefined,
                    spouseDrillTo: false,
                    primarySpouseId: undefined,
                    spousePhoto: undefined,
                    path: undefined,
                };

            }
        }
        // family without parent
        else {
            // family without parent and with spouse, so the spouse is the parent
            if (spouse.length > 0) {
                parentPerson = {
                    id: spouse[0].pointer,
                    personId: spouse[0].pointer,
                    label1: get_name(spouse),
                    label2: get_name(spouse),
                    name: get_name(spouse),
                    gender: get_sex(spouse),
                    birth: get_birth_date(spouse),
                    death: get_death_date(spouse),
                    photo: undefined,
                    parentId: undefined,
                    personOrder: undefined,
                    childOrder: undefined,
                    spouseId: undefined,
                    spouseName: undefined,
                    spouseLabel1: undefined,
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
            parentPerson.primarySpouseId = personExist[0].id
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

            // check if child is adopted
            const { adopted, adoptedType } = is_adopted(child,family[0].pointer)
            
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
                const childPerson  = {
                    id: child[0].pointer,
                    personId: child[0].pointer,
                    label1: get_name(child),
                    label2: get_name(child),
                    name: get_name(child),
                    gender: get_sex(child),
                    birth: get_birth_date(child),
                    death: get_death_date(child),
                    photo: undefined,
                    parentId: parentId,
                    adopted : adopted,
                    adoptedType : adoptedType,
                    personOrder: undefined,
                    childOrder: undefined,
                    spouseId: undefined,
                    spouseName: undefined,
                    spouseLabel1: undefined,
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
        let indiPerson = {
            id: indiv[0].pointer,
            personId: indiv[0].pointer,
            label1: get_name(indiv),
            label2: get_name(indiv),
            name: get_name(indiv),
            gender: get_sex(indiv),
            birth: get_birth_date(indiv),
            death: get_death_date(indiv),
            photo: undefined,
            parentId: undefined,
            personOrder: undefined,
            childOrder: undefined,
            spouseId: undefined,
            spouseName: undefined,
            spouseLabel1: undefined,
            spouseGender: undefined,
            spouseOrder: undefined,
            spouseDrillTo: false,
            primarySpouseId: undefined,
            spousePhoto: undefined,
            path: undefined,
        };

        individualRecords[indiPerson.id] = indiPerson;
        return;
    }

    // if families length > 0, iterate over all families and get couples
    get_couples(families,individualRecords)

    // if families length > 0, iterate over all families and get children
    get_children(families, individualRecords)

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
    data.sort((a, b) => parseDate(a.birth) - parseDate(b.birth));


    console.log(data)
    return (data)

}

function parseDate(dateStr) {
    if (!dateStr) return new Date(0); // For undefined dates, return a very early date
    const parts = dateStr.split(' ');

    if (parts.length === 1) {
        // Only year is given
        return new Date(parts[0], 0, 1); // Use January 1st of that year
    } else {
        // Full date is given
        const [day, month, year] = parts;
        const monthNames = ["JAN", "FEB", "MAR", "APR", "MAY", "JUN", "JUL", "AUG", "SEP", "OCT", "NOV", "DEC"];
        const monthIndex = monthNames.indexOf(month);
        return new Date(year, monthIndex, day);
    }
}