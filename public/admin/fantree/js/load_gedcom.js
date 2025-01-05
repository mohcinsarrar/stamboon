
// the function draw_tree load gedcom file from API '/pedigree/getTree' 
// next, its parse data to transform gedcom file to accepted structure for d3-org-chart
function draw_tree() {

    // load gedcom file from api for the current user
    const promise = fetch('/fantree/getTree')
        .then(r => {
            if (r.status == 404) {
                throw new Error(`HTTP error! Status: ${r.status}`);
            }
            return r.arrayBuffer()
        })
        .then(Gedcom.readGedcom)
        .catch(error => {
            //console.log(error)
            if (document.getElementById("add-first-person-container") != null) {
                document.getElementById("add-first-person-container").classList.remove('d-none');
            }
            return false;
        });

    promise.then(gedcom => {
        // transform readGedcom structure to a structure accepted from d3-org-chart
        if (!gedcom) return;

        const treeData = transformGedcom(gedcom);
        familyData = treeData;


        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: "/pedigree/settings",
            type: 'GET',
            dataType: 'json',
            success: function (data) {
                if (data.error == false) {
                    console.log(data)
                    treeConfiguration = data.settings

                    // after loading settings draw chart with treeData and treeConfiguration
                    renderChart();
                    if(document.getElementById("add-first-person-container") != null){
                        document.getElementById("add-first-person-container").remove();
                    }
                    

                } else {
                    show_toast('error', 'error', data.error)
                }

            },
            error: function (xhr, status, error) {
                if ('responseJSON' in xhr) {
                    show_toast('error', 'error', xhr.responseJSON.message)
                } else {
                    show_toast('error', 'error', error)
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
    var codedName;
    var firstname, lastname
    if (name.length > 0) {
        codedName = name[0].value.replace(/\//g, " ").trimEnd()
    }
    else {
        return {
            'firstname': '',
            'lastname': ''
        }
    }

    codedName = codedName.replace(/\s+/g, ' ');

    splitName = codedName.split(" ")
    firstname = splitName[0]
    lastname = splitName.slice(1).join(" ")

    return {
        'firstname': firstname,
        'lastname': lastname
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
    const personObject = individualRecords[personId];
    

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


    families.forEach((family, key, array) => {

        // check if family is an object
        if (!isObject(family[0])) {
            return;
        }

        // get husband, wife and child
        husband = family.getHusband().getIndividualRecord()
        wife = family.getWife().getIndividualRecord()
        child = family.getChild().getIndividualRecord()

        husband_id = husband[0].pointer
        wife_id = wife[0].pointer
        child_id = child[0].pointer

        husband = parsePerson(husband)
        wife = parsePerson(wife)
        child = parsePerson(child)

        individualRecords[husband_id] = husband
        individualRecords[wife_id] = wife
        individualRecords[child_id] = child

        // Map the child to their parents
        childToParents[child_id] = { husband_id, wife_id };

    });

    // get root person
    potentialRoots = getRoot(childToParents)

    // Build trees for each root
    const familyTrees = potentialRoots.map(root => buildFamilyTree(individualRecords,childToParents,root));

    return familyTrees[0];


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

