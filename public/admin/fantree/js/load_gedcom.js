
// the function draw_tree load gedcom file from API '/fantree/getTree' 
// next, its parse data to transform gedcom file to accepted structure for d3-org-chart
function draw_tree() {

    if(chart != undefined){
        editChartStatus()
    }
    load_settings()
    applyChartStatus()
    disable_tools_bar()
    
    // load gedcom file from api for the current user
    const promise = fetch('/fantree/getTree')
        .then(r => {
            if (r.status == 404) {
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
            
            show_toast('danger', 'error', error)
            return false;
        });

    promise.then(gedcom => {
        
        // transform readGedcom structure to a structure accepted from d3-org-chart
        if (!gedcom) return;

        let treeData;

        try {
            treeData = transformGedcom(gedcom);
        } catch (error) {
            show_toast('danger', 'error', error)
            return;
        }
        
        familyData = treeData;


        
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: "/fantree/settings",
            type: 'GET',
            dataType: 'json',
            success: function (data) {
                if (data.error == false) {

                    treeConfiguration = data.settings

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




// check if an individual exist
function personIdExists(people, id) {
    return Object.values(people).filter(person => person.personId === id);
}





function parsePerson(person) {
    var personPhoto = undefined;
    if (person.getNote()[0] != undefined) {
        personPhoto = person.getNote()[0].value;
    }

    var names = get_name(person)
    personObject = {
        id: person[0].pointer,
        firstname: names['firstname'],
        lastname: names['lastname'],
        gender: get_sex(person),
        status: get_status(person),
        birth: get_birth_date(person),
        death: get_death_date(person),
        photo: personPhoto,
        personOrder: undefined,
    };

    return personObject
}


function getRoot(childToParents) {
    // Determine the roots (children who are not husbands or wives)
    const allHusbands = new Set(Object.values(childToParents).map(rel => rel.husband_id));
    const allWives = new Set(Object.values(childToParents).map(rel => rel.wife_id));
    const allChildren = Object.keys(childToParents);

    const potentialRoots = allChildren.filter(child => !allHusbands.has(child) && !allWives.has(child));

    return potentialRoots

}

function buildFamilyTree(individualRecords,childToParents,personId, order = 0) {
    
    const parents = childToParents[personId];
    let personObject = individualRecords[personId];

    if (!parents) {
        // If the person has no parents, return just their ID
        return {
            ...personObject,
            order:order,
            parents:[]
        };
    }

    // Recursively build the parents' subtrees
    const husbObject = individualRecords[parents.husband_id];
    const wifeObject = individualRecords[parents.wife_id];
    return {
        ...personObject,
        order:order,
        parents: [
            buildFamilyTree(individualRecords,childToParents,parents.husband_id,1),
            buildFamilyTree(individualRecords,childToParents,parents.wife_id,2)
            
        ],
    };
}


// parse gedcom file 
function transformGedcom(gedcom) {
    let individualRecords = [];
    const childToParents = {};

    // get all families
    families = gedcom.getFamilyRecord().arraySelect()

    if (families.length == 0) {
        let indi = gedcom.getIndividualRecord().arraySelect()[0];
        
        indi = parsePerson(indi)
        indi.parents = []
        indi.order = 0

        return indi;
        
    }
    else{
        // 63 families in total for 7 generations
        
        families.forEach((family, key, array) => {

            // check if family is an object
            if (!isObject(family[0])) {
                return;
            }
    
            // get husband, wife and child
            husband = family.getHusband().getIndividualRecord()
            wife = family.getWife().getIndividualRecord()
            child = family.getChild().getIndividualRecord()
    
            husband_id = husband[0] != undefined ? husband[0].pointer : null
            wife_id = wife[0] != undefined ? wife[0].pointer : null
            child_id = child[0].pointer
    
            husband = husband_id != null ? parsePerson(husband) : null
            wife = wife_id != null ? parsePerson(wife) : null
            child = parsePerson(child)
    
            individualRecords[husband_id] = husband
            individualRecords[wife_id] = wife
            individualRecords[child_id] = child
    
            // Map the child to their parents
            childToParents[child_id] = { husband_id, wife_id };
            
    
        });

        // get root person
        potentialRoots = getRoot(childToParents)

        // add persons to get an equal fantree
        /*
        let generations = calculateGenerations(childToParents,potentialRoots[0]);
        let maxGeneration = Math.max(...Object.values(generations));
        generateAncestors(childToParents,individualRecords,generations,maxGeneration)
        */
        //complete_tree(childToParents,potentialRoots[0])
        

        // 63 families and 127 individus

        
        // Build trees for each root
        const familyTrees = potentialRoots.map(root => buildFamilyTree(individualRecords,childToParents,root));

        return familyTrees[0];

        
    }

    


}

function complete_tree(childToParents,potentialRoots){
    let generations = calculateGenerations(childToParents,potentialRoots);

    const countByGenerations = Object.values(generations).reduce((acc, value) => {
        acc[value] = (acc[value] || 0) + 1;
        return acc;
    }, {});

    
    for (let generation_number = 2; generation_number <= 7; generation_number++) {

        let generation = countByGenerations[generation_number];

        if(generation != undefined && generation <  Math.pow(2, generation_number-1)){
            let childs = Object.keys(generations).filter(key => generations[key] == generation_number-1);
            const difference = childs.filter(item => !Object.keys(childToParents).includes(item));
            difference.forEach(key => {
                if (!childToParents[key]) {
                    childToParents[key] = { husband_id: null, wife_id: null };
                }
            });
        }

    }
    

    


}

function calculateGenerations(familyTree, root) {
    let generations = {};
    let visited = new Set();  // To track visited nodes and avoid cycles
  
    function assignGeneration(child, generation) {
      if (!visited.has(child)) {
        visited.add(child);
        generations[child] = generation;
        
        const parents = familyTree[child];
        if (parents) {
          // Recursive calls for parents
          assignGeneration(parents.husband_id, generation + 1);
          assignGeneration(parents.wife_id, generation + 1);
        }
      }
    }
  
    // Assuming "@I1@" is the root (you can change this based on your root child)
    assignGeneration(root, 1);
  
    return generations;
  }
let uniqueCounter = 1
function generateAncestors(families, individualRecords,generations,maxGeneration) {
    let husbands = new Set();
    let wives = new Set();
    let children = new Set(Object.keys(families)); // The keys are the children

    // Identify husbands and wives from families
    Object.entries(families).forEach(([child, { husband_id, wife_id }]) => {
        husbands.add(husband_id);
        wives.add(wife_id);
    });

    // Process husbands
    Array.from(husbands).forEach((husbandId) => {
        if (!children.has(husbandId) && generations[husbandId] < maxGeneration) {
            let newHusbandId = `@HH${uniqueCounter++}`;
            let newWifeId = `@HW${uniqueCounter++}`;
            families[husbandId] = { husband_id: newHusbandId, wife_id: newWifeId };
            individualRecords[newHusbandId] = {id: newHusbandId};
            individualRecords[newWifeId] = {id: newWifeId};

        }
    });

    // Process wives
    Array.from(wives).forEach((wifeId) => {
        if (!children.has(wifeId) && generations[wifeId] < maxGeneration) {
            let newHusbandId = `@WH${uniqueCounter++}`;
            let newWifeId = `@WW${uniqueCounter++}`;
            families[wifeId] = { husband_id: newHusbandId, wife_id: newWifeId };
            individualRecords[newHusbandId] = {id: newHusbandId};
            individualRecords[newWifeId] = {id: newWifeId};
        }
    });

}


function parseDateToSort(dateStr) {
    if (!dateStr) return new Date(0); // For undefined dates, return a very early date
    const parts = dateStr.split(' ');

    if (parts.length === 1) {
        // Only year is given
        return new Date(parts[0], 0, 1); // Use January 1st of that year
    } else {
        // Full date is given
        const [day, month, year] = parts;
        const monthNames = ["JAN", "FEB", "MAR", "APR", "MAY", "JUN", "JUL", "AUG", "SEP", "OCT", "NOV", "DEC"];
        const monthIndex = monthNames.indexOf(month) + 1;
        return new Date(year, monthIndex, day);
    }
}

