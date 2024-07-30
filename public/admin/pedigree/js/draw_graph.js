// init variables //
const maleIcon = '/storage/portraits/male2.gif';
const femaleIcon = '/storage/portraits/female2.gif';
var chart;
var familyData = [];
var selectedPersonId = -1;
var prevSelectedPersonId = -1;
var constants = {
    rootId: "hidden_root",
}
var treeConfiguration = {
    chartContainer: '#graph', // root svg

    // height & width
    nodeWidth: 190,
    nodeWidthSpouse: 400,
    get nodeWidthDiff() {
        return this.nodeWidthSpouse - this.nodeWidth * 2;
    },
    get linkShift() {
        return Math.round((this.nodeWidth + this.nodeWidthDiff) / 2);// for moving the link coming from parent
    },
    nodeHeight: 60,
    nodeHeightSpouse: 60,

    // margins
    siblingsMargin: 23,
    childrenMargin: 60,
    neighbourMargin: 13,
    rootMargin: 0,

    // child & spouse lines
    linkStroke: 'lightgray',
    linkStrokeWidth: 2,
    connectionStroke: 'lightgray',
    connectionStrokeWidth: 2,

    // expand collapse button
    nodeButtonWidth: '',
    nodeButtonHeight: '',
    nodeButtonX: -20,
    nodeButtonY: -10,
}

// render chart //
function renderChart(data) {
    familyData = data;
    chart = new d3.OrgChart()
        .container(treeConfiguration.chartContainer)
        .data(data)
        .initialExpandLevel(5)
        .layout('top')
        .onNodeClick((nodeId) => nodeClicked(nodeId))
        .rootMargin(treeConfiguration.rootMargin)
        .nodeWidth((d) => {
            if (d.data.id === constants.rootId) return 0;
            if (d.data.primarySpouseId !== undefined) {
                return treeConfiguration.nodeWidth;
            }
            return d.data.spouseId !== undefined ?
                treeConfiguration.nodeWidthSpouse :
                treeConfiguration.nodeWidth;
        })

        .nodeHeight((d) => {
            if (d.data.id === constants.rootId) return 0;
            else
                return d.data.spouseId !== undefined ?
                    treeConfiguration.nodeHeightSpouse :
                    treeConfiguration.nodeHeight;
        })
        .neighbourMargin(() => treeConfiguration.neighbourMargin)
        .childrenMargin(() => treeConfiguration.childrenMargin)
        .siblingsMargin(() => treeConfiguration.siblingsMargin)
        .nodeButtonY(() => treeConfiguration.nodeButtonY)
        .nodeButtonX(() => treeConfiguration.nodeButtonX)
        .linkUpdate(function (d) {

            // drawing the connecting line
            if (
                d.data.parentId === constants.rootId ||
                d.data.primarySpouseId !== undefined
            ) {
                return;
            } else {
                d3.select(this)
                    .attr('stroke', () => treeConfiguration.linkStroke)
                    .attr('stroke-width', () => treeConfiguration.linkStrokeWidth);
            }
        })
        .nodeUpdate(function (d, i, arr) {
            if (d.data.id == 'hidden_root') {
                d3.select(this).style('visibility', 'hidden');
            }

        })
        .connectionsUpdate(function () {
            d3.select(this).lower()
                .attr('stroke', () => treeConfiguration.connectionStroke)
                .attr('stroke-width', () => treeConfiguration.connectionStrokeWidth);
        })
        .nodeContent(function (d) {
            const personData = d.data;

            let extraCss = '';
            if (personData.primarySpouseId !== undefined) {
                if (personData.gender === 'M') {
                    extraCss = 'female-spouse';
                } else {
                    extraCss = 'male-spouse';
                }
            }

            let nodeHtml = `<div class="row mx-0 ${extraCss}">`;
            if (personData.primarySpouseId !== undefined) {
                // additional spouses
                nodeHtml += getPersonNodeContent(personData, 'spouse');
            } else if (personData.gender === 'F') {
                nodeHtml += getPersonNodeContent(personData, 'spouse');
                nodeHtml += getPersonNodeContent(personData, 'person');
            } else {
                nodeHtml += getPersonNodeContent(personData, 'person');
                nodeHtml += getPersonNodeContent(personData, 'spouse');
            }
            nodeHtml += '</div>';

            return nodeHtml;
        })
        .compact(false);

    // changing the links for persons who has spouse
    chart.layoutBindings().top.linkX = (d) => {
        let x = d.x;
        if (d.data === undefined) {
            // Using x & y locations get the corresponding person data
            const allNodes = chart.getChartState().allNodes;
            allNodes.forEach((node) => {
                if (node.x === d.x && node.y === d.y) {
                    if (node.data.gender === 'M') {
                        x = d.x + d.width / 2;
                    } else {
                        x = d.x - d.width / 2;
                    }
                }
            });
        } else if (d.data.spouseId !== undefined && d.data.gender === 'M') {
            x = d.x - treeConfiguration.linkShift; // for parent to child link
        } else if (d.data.spouseId !== undefined && d.data.gender === 'F') {
            x = d.x + treeConfiguration.linkShift; // for parent to child link
        } else {
            x = d.x;
        }

        return x;
    };

    chart.layoutBindings().top.linkY = (d) => {
        if (d.data === undefined) {
            // connections
            return d.y + d.height / 2;
        } else {
            return d.y;
        }
    };

    chart.layoutBindings().top.linkJoinX = (d) => {
        let x = d.x;
        if (d.data === undefined) {
            // connections
            // Using x & y locations get the corresponding person data
            const allNodes = chart.getChartState().allNodes;
            allNodes.forEach((node) => {
                if (node.x === d.x && node.y === d.y) {
                    if (node.data.gender === 'M') {
                        x = d.x - d.width / 2 - 1;
                    } else {
                        x = d.x + d.width / 2 + 1;
                    }
                }
            });
        } else {
            x = d.x;
        }

        return x;
    };

    chart.layoutBindings().top.linkJoinY = (d) => {

        if (d.data === undefined) {
            // connections
            return d.y + d.height / 2;
        } else {
            return d.y;
        }
    };

    // checking for multiple spouses
    const multipleSpouseConnections = [];
    data.forEach((model) => {
        if (model.primarySpouseId !== undefined) {
            // additional spouses
            multipleSpouseConnections.push({
                from: model.primarySpouseId,
                to: model.id,
                label: '',
            });
        }
    });

    chart.connections(multipleSpouseConnections);

    chart.render();

    // change connections order (lower)
    const chartElement = document.querySelector('.center-group');
    const thirdGroup = document.querySelector('.connections-wrapper');

    // Move the third group to the first position
    chartElement.insertBefore(thirdGroup, chartElement.firstChild);


    // add tools zoom, expand ...
    $(document).on("click", "#zoomIn", function () {
        chart.zoomIn()
    });

    $(document).on("click", "#zoomOut", function () {
        chart.zoomOut()
    });

    $(document).on("click", "#viewVertical", function () {
        chart.layout('top').render().fit()
    });

    $(document).on("click", "#viewHorizontal", function () {
        chart.layout('left').render().fit()
    });

    $(document).on("click", "#compactView", function () {

        if ($('#compactView').data('compact') == false) {
            chart.compact(true).render().fit()
            $('#compactView').data('compact', true)
            $('#compactView').html('Decompact')
        } else {
            chart.compact(false).render().fit()
            $('#compactView').data('compact', false)
            $('#compactView').html('Compact')
        }

    });

    $(document).on("click", "#expandView", function () {
        chart.expandAll().render().fit();
    });

    $(document).on("click", "#collpaseView", function () {

        const {
            allNodes,
            root
        } = chart.getChartState();
        allNodes.forEach(d => d.data._expanded = false);
        chart.initialExpandLevel(1)
        chart.render().fit();
    });

}

// generate NodeContent
function getPersonNodeContent(personData, personType) {
    // get the layout type to change nodeContent depending on layout type
    //console.log(chart.layout())
    const person = {};

    if (personType === 'spouse') {
        if (personData.spouseId !== undefined) {
            person.personId = personData.spouseId;
        } else {
            return '';
        }
        person.personName = personData.spouseName;
        person.gender = personData.spouseGender;
        person.status = personData.spouseStatus;
        person.birth = personData.spouseBirth;
        person.death = personData.spouseDeath;
        person.photo = personData.spousePhoto;
    } else {
        person.personId = personData.personId;
        person.personName = personData.name;
        person.gender = personData.gender;
        person.status = personData.status;
        person.birth = personData.birth;
        person.death = personData.death;
        person.photo = personData.photo;
    }

    let personCssClass, personIcon;
    let photoClass = '';
    if (person.gender === 'M') {
        personCssClass = 'male-member';
        personIcon = maleIcon;
    } else {
        personCssClass = 'female-member';
        personIcon = femaleIcon;
    }

    if (person.photo !== undefined && person.photo !== null) {
        personIcon = person.photo;
        photoClass = 'profile-photo';
    }

    let nodeContent = '';
    if (
        personData.spouseId !== undefined &&
        person.gender === 'F' &&
        personData.primarySpouseId === undefined
    ) {
        nodeContent += '<div class="line"><hr/></div>';
    }

    let selectedPersonCssClass = '';
    if (selectedPersonId === person.personId) {
        selectedPersonCssClass = 'selected-person';
    }

    let drillToHide = 'hide';
    if (personType === 'spouse' && personData.spouseDrillTo) {
        drillToHide = ''; // hide drill to icon
    }

    let adoptiveChild = '';
    if (personData.adopted == true) {
        adoptiveChild = 'adoptive-child'
    }

    nodeContent += `
          <div class="col px-0 person-member person-${person.personId} ${selectedPersonCssClass} ">
            
            <div class="card rounded-0 w-100 overflow-hidden ${personCssClass}  ${adoptiveChild}" onClick="window.personClicked='${person.personId}';" >
                ${is_death(person.status)}
                <div class="row g-0 mx-0 overflow-hidden">
                <div class="col-4">
                    <img class="card-img card-img-left rounded-0 object-fit-cover" src="${personIcon}" alt="Card image">
                </div>
                <div class="col-8 align-items-center d-flex">
                    <div class="card-body p-1 ms-1">
                    <p class="card-title mb-1 ellipsis">${person.personName}</p>
                    <p class="card-text"><small class="">${getFullDate(person.birth, person.death)}</small></p>
                    </div>
                </div>
                </div>
            </div>
          </div>`;
    return nodeContent;
    //}
}

function is_death(status) {
    if (status == "Deceased") {
        return `<div class="diagonal-text"><span></span></div>`;
    }
    else {
        return "";
    }
}

function parseDate(dateStr) {

    const parts = dateStr.split(' ');
    var day,month,year;
    
    if (parts.length === 1) {
        // Only year is given
         // Use January 1st of that year
        day = null
        month = null
        year = parts[0]
        
    } else {
        // Full date is given
        const [dayName, monthName, yearName] = parts;
        const monthNames = ["JAN", "FEB", "MAR", "APR", "MAY", "JUN", "JUL", "AUG", "SEP", "OCT", "NOV", "DEC"];
        const monthIndex = monthNames.indexOf(monthName)+1;
        day = dayName
        month = monthIndex< 10 ? "0"+monthIndex.toString() : monthIndex.toString() 
        year = yearName
    }

    return {day,month,year};

}


function getFullDate(birth, death) {


    if (death != null) {
        var {day,month,year} = parseDate(death);
        deathDate = year;
    }
    else {
        deathDate = '';
    }


    if (birth != null) {
        var {day,month,year} = parseDate(birth);
        birthDate = year;
    }
    else {
        birthDate = '';
    }

    if (birthDate == '' && deathDate == '') {
        return ''
    }

    return `(${birthDate}-${deathDate})`


}

function nodeClicked(id) {

    const person = familyData.filter((p) => p.personId === window.personClicked);
    var personInfo = {}
    if(person.length > 0){
        personInfo.personId = person[0].personId
        personInfo.name = person[0].name
        personInfo.status = person[0].status
        personInfo.birth = person[0].birth
        personInfo.death = person[0].death
    }
    else{
        
        const spouse = familyData.filter((p) => p.spouseId === window.personClicked);
        personInfo.personId = spouse[0].spouseId
        personInfo.name = spouse[0].spouseName
        personInfo.status = spouse[0].spouseStatus
        personInfo.birth = spouse[0].spouseBirth
        personInfo.death = spouse[0].spouseDeath
    }

    show_profile(personInfo)
}

function clearForm(formId) {
    const form = document.getElementById(formId);
    const inputs = form.querySelectorAll('input');
    const selects = form.querySelectorAll('select');

    inputs.forEach(input => {
        switch (input.type) {
            case 'checkbox':
            case 'radio':
                input.checked = false;
                break;
            default:
                if(input.name != "_token"){
                    input.value = '';
                }
                    
        }
    });

    selects.forEach(select => {
        select.selectedIndex = 0;
    });
}

// toggle death container view
document.querySelector('#formUpdatePerson #deceased').addEventListener('change', (event) => {
   if(event.target.checked){
    document.querySelector('#formUpdatePerson #death-container').classList.remove("d-none");
   }
   else{
    document.querySelector('#formUpdatePerson #death-container').classList.add("d-none");
   }
});

document.querySelector('#formUpdatePerson #living').addEventListener('change', (event) => {
    if(event.target.checked){
     document.querySelector('#formUpdatePerson #death-container').classList.add("d-none");
    }
    else{
     document.querySelector('#formUpdatePerson #death-container').classList.remove("d-none");
    }
 });

function show_profile(personInfo){
    // clear update form
    clearForm("formUpdatePerson")
    document.querySelector('#formUpdatePerson #death-container').classList.remove("d-none");
    
    // fill person id
    document.querySelector('#formUpdatePerson #person_id').value = personInfo.personId

    // split name to first and last name
    names_array = personInfo.name.split(' ')
    document.querySelector('#formUpdatePerson #firstname').value = names_array.slice(0, -1).join(' ')
    document.querySelector('#formUpdatePerson #lastname').value = names_array[names_array.length - 1]

    // check living or deceased radioButton
    if(personInfo.status == 'Deceased'){
        document.getElementById('deceased').checked = true;
    }
    else{
        document.getElementById('living').checked = true;
    }

    // fill birth if exist
    if(personInfo.birth != null){

        var {day,month,year} = parseDate(personInfo.birth);

        document.querySelector('#formUpdatePerson #date_birth input[aria-label="day"]').value = day
        document.querySelector('#formUpdatePerson #date_birth input[aria-label="month"]').value = month
        document.querySelector('#formUpdatePerson #date_birth input[aria-label="year"]').value = year
    }

    // show death date input if Deceased
    if(personInfo.status == 'Deceased'){
        // fill death if exist
        if(personInfo.death != null){

            var {day,month,year} = parseDate(personInfo.death);
    
            document.querySelector('#formUpdatePerson #date_death input[aria-label="day"]').value = day
            document.querySelector('#formUpdatePerson #date_death input[aria-label="month"]').value = month
            document.querySelector('#formUpdatePerson #date_death input[aria-label="year"]').value = year
        }
    }
    // hide death date id living
    else{

        document.querySelector('#formUpdatePerson #death-container').classList.add("d-none");
    }
    

    
    //document.querySelector('#formUpdatePerson #birth_date').value = personInfo.birth
    //document.querySelector('#formUpdatePerson #death_date').value = personInfo.death

    var myOffcanvas = document.getElementById('offcanvasUpdatePerson')
    var bsOffcanvas = new bootstrap.Offcanvas(myOffcanvas)
    bsOffcanvas.show()
}

// send update form
document.getElementById('formUpdatePerson').addEventListener('submit', function(event) {
    const form = event.target;
    let isValid = true;
    let msg = "<ul>"

    // validate first name
    if(form.querySelector('#firstname').value == ""){
        isValid = false
        msg += "<li>First and middle name is required</li>"
    }

    // validate last name
    if(form.querySelector('#lastname').value == ""){
        isValid = false
        msg += "<li>First and middle name is required</li>"
    }

    // validate last name
    if(form.querySelector('#lastname').value == ""){
        isValid = false
        msg += "<li>First and middle name is required</li>"
    }

    if(form.querySelector('#living').check == false && form.querySelector('#deceased').value == false){ 
        isValid = false
        msg += "<li>Status must be checked</li>"
    }

    msg += "</ul>"

    if (!isValid) {
        event.preventDefault(); // Prevent form submission
        show_toast('danger', 'error', msg)
    }
});
