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
        return Math.round((this.nodeWidth + this.nodeWidthDiff) / 2) + 1;// for moving the link coming from parent
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
                d3.select(this).style('display', 'none');
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
            
            <div class="card rounded-0 w-100 overflow-hidden ${personCssClass}  ${adoptiveChild}" onClick="window.personClicked='${person.personId}';" data-personId="${person.personId}">
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
    var day, month, year;

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
        const monthIndex = monthNames.indexOf(monthName) + 1;
        day = dayName
        month = monthIndex < 10 ? "0" + monthIndex.toString() : monthIndex.toString()
        year = yearName
    }

    return { day, month, year };

}

function parseDateEdit(dateStr) {

    const parts = dateStr.split(' ');
    var day, month, year;

    if (parts.length === 1) {
        // Only year is given
        // Use January 1st of that year
        day = null
        month = null
        year = parts[0]

        return "" + year + "";

    } else {
        // Full date is given
        const [dayName, monthName, yearName] = parts;
        const monthNames = ["JAN", "FEB", "MAR", "APR", "MAY", "JUN", "JUL", "AUG", "SEP", "OCT", "NOV", "DEC"];
        const monthIndex = monthNames.indexOf(monthName) + 1;
        day = dayName
        month = monthIndex < 10 ? "0" + monthIndex.toString() : monthIndex.toString()
        year = yearName

        return year + "-" + month + "-" + day;
    }



}


function getFullDate(birth, death) {


    if (death != null) {
        var { day, month, year } = parseDate(death);
        deathDate = year;
    }
    else {
        deathDate = '';
    }


    if (birth != null) {
        var { day, month, year } = parseDate(birth);
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

function nodeClicked(d) {

    // check if offcanvas is open
    var offcanvasElement = document.getElementById('offcanvasUpdatePerson');
    if (offcanvasElement.classList.contains('show')) {
        return;
    }
    var offcanvasElement = document.getElementById('offcanvasAddSpouse');
    if (offcanvasElement.classList.contains('show')) {
        return;
    }
    var offcanvasElement = document.getElementById('offcanvasAddChild');
    if (offcanvasElement.classList.contains('show')) {
        return;
    }
    const person = familyData.filter((p) => p.personId === window.personClicked);
    var personInfo = {}
    var persTest = {};
    if (person.length > 0) {
        personInfo.personId = person[0].personId
        personInfo.name = person[0].name
        personInfo.status = person[0].status
        personInfo.birth = person[0].birth
        personInfo.death = person[0].death
        personInfo.spouseIds = person[0].spouseIds
        personInfo.type = 'person'
    }
    else {

        const spouse = familyData.filter((p) => p.spouseId === window.personClicked);
        personInfo.personId = spouse[0].spouseId
        personInfo.name = spouse[0].spouseName
        personInfo.status = spouse[0].spouseStatus
        personInfo.birth = spouse[0].spouseBirth
        personInfo.death = spouse[0].spouseDeath
        personInfo.type = 'spouse'
        personInfo.personBeforeSpouseId = spouse[0].personId
    }


    const modal = document.getElementById('nodeModal');
    const modalBody = document.getElementById('nodeModalBody');

    modalBody.querySelector('.name').innerHTML = personInfo.name;
    modalBody.querySelector('.personImage').src = maleIcon;
    modalBody.querySelector('.birth').innerHTML = personInfo.birth;
    modalBody.querySelector('.death').innerHTML = personInfo.death;

    // buttons click
    modalBody.querySelector('#nodeEdit').addEventListener('click', (event) => {
        edit_profile(personInfo)
    });
    modalBody.querySelector('#nodeDelete').addEventListener('click', (event) => {
        delete_profile(personInfo)
    });
    modalBody.querySelector('#addSpouse').addEventListener('click', (event) => {
        add_spouse(personInfo)
    });
    modalBody.querySelector('#addChild').addEventListener('click', (event) => {
        add_child(personInfo)
    });
    // Get the node's position

    const node = document.querySelector('[data-personId="' + window.personClicked + '"]').getBoundingClientRect();


    // Position the modal
    modal.style.left = `${node.left}px`;
    modal.style.top = `${node.top}px`;

    // Show the modal
    modal.style.display = 'block';
}

function close_custom_modal() {
    var offcanvasElement = document.getElementById('offcanvasUpdatePerson');
    if (offcanvasElement.classList.contains('show')) {
        return;
    }
    var offcanvasElement = document.getElementById('offcanvasAddSpouse');
    if (offcanvasElement.classList.contains('show')) {
        return;
    }
    var offcanvasElement = document.getElementById('offcanvasAddChild');
    if (offcanvasElement.classList.contains('show')) {
        return;
    }
    const modal = document.getElementById('nodeModal');
    modal.style.display = 'none';
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
                if (input.name != "_token") {
                    input.value = '';
                }

        }
    });

    selects.forEach(select => {
        select.selectedIndex = 0;
    });
}


function delete_profile(personInfo) {

    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        customClass: {
            confirmButton: 'btn btn-primary me-3',
            cancelButton: 'btn btn-label-secondary'
        },
        buttonsStyling: false
      }).then(function (result) {
        if (result.value) {
            formDeletePerson = document.getElementById('formDeletePerson');
            formDeletePerson.querySelector('.person_id').value = personInfo.personId;
            formDeletePerson.submit();
        }
      });

    
}


function edit_profile(personInfo) {
    // clear update form
    clearForm("formUpdatePerson")
    formUpdatePerson = document.getElementById('formUpdatePerson');
    formUpdatePerson.querySelector('.death-container').classList.remove("d-none");

    // fill person id
    formUpdatePerson.querySelector('.person_id').value = personInfo.personId

    // split name to first and last name
    names_array = personInfo.name.split(' ')
    formUpdatePerson.querySelector('.firstname').value = names_array.slice(0, -1).join(' ')
    formUpdatePerson.querySelector('.lastname').value = names_array[names_array.length - 1]

    // check living or deceased radioButton
    if (personInfo.status == 'Deceased') {
        formUpdatePerson.querySelector('.deceased').checked = true;
    }
    else {
        formUpdatePerson.querySelector('.living').checked = true;
    }

    // fill birth if exist
    if (personInfo.birth != null) {

        var birthDate = parseDateEdit(personInfo.birth);
        formUpdatePerson.querySelector('.birth_date').value = birthDate
    }

    // toggle death container view
    formUpdatePerson.querySelector('.deceased').addEventListener('change', (event) => {
        if (event.target.checked) {
            formUpdatePerson.querySelector('.death-container').classList.remove("d-none");
        }
        else {
            formUpdatePerson.querySelector('.death-container').classList.add("d-none");
        }
    });

    formUpdatePerson.querySelector('.living').addEventListener('change', (event) => {
        if (event.target.checked) {
            formUpdatePerson.querySelector('.death-container').classList.add("d-none");
        }
        else {
            formUpdatePerson.querySelector('.death-container').classList.remove("d-none");
        }
    });

    // show death date input if Deceased
    if (personInfo.status == 'Deceased') {
        // fill death if exist
        if (personInfo.death != null) {

            var deathDate = parseDateEdit(personInfo.death);
            formUpdatePerson.querySelector('.death_date').value = deathDate
        }
    }
    // hide death date id living
    else {

        formUpdatePerson.querySelector('.death-container').classList.add("d-none");
    }

    var myOffcanvas = document.getElementById('offcanvasUpdatePerson')
    var bsOffcanvas = new bootstrap.Offcanvas(myOffcanvas)
    bsOffcanvas.show()



}

// validate and send update form
document.getElementById('formUpdatePerson').addEventListener('submit', function (event) {
    const form = event.target;
    let isValid = true;
    let msg = "<ul>"

    // validate first name
    if (form.querySelector('.firstname').value == "") {
        isValid = false
        msg += "<li>First and middle name is required</li>"
    }

    // validate last name
    if (form.querySelector('.lastname').value == "") {
        isValid = false
        msg += "<li>Last name is required</li>"
    }

    // validate status

    if (form.querySelector('.living').check == false && form.querySelector('.deceased').value == false) {
        isValid = false
        msg += "<li>Status must be checked</li>"
    }

    // validate birth date
    const regexFullDate = /^\d{4}-\d{2}-\d{2}$/;
    const regexYearOnly = /^\d{4}$/;

    var birth_date = form.querySelector('.birth_date').value
    if (birth_date != '') {
        if (regexFullDate.test(birth_date) || regexYearOnly.test(birth_date)) {
            if (!isValidDate(birth_date)) {
                isValid = false
                msg += "<li>Please enter a valid birth date</li>"
            }
        }
        else {
            isValid = false
            msg += "<li>Please enter a valid birth date</li>"
        }
    }


    // validate death date
    var death_date = form.querySelector('.death_date').value
    if (death_date != '') {
        if (regexFullDate.test(death_date) || regexYearOnly.test(death_date)) {
            if (!isValidDate(death_date)) {
                isValid = false
                msg += "<li>Please enter a valid death date</li>"
            }
        }
        else {
            isValid = false
            msg += "<li>Please enter a valid death date</li>"
        }
    }


    // test if death date is after birth date
    if (death_date != '' && birth_date != '') {
        var death_full_date = ''
        if (regexFullDate.test(death_date)) {
            death_full_date = death_date
        }
        if (regexYearOnly.test(death_date)) {
            death_full_date = death_date + '-01-01'
        }

        var birth_full_date = ''
        if (regexFullDate.test(birth_date)) {
            birth_full_date = birth_date
        }
        if (regexYearOnly.test(birth_date)) {
            birth_full_date = birth_date + '-01-01'
        }
        console.log(compareDates(birth_full_date, death_full_date));
        if (compareDates(birth_full_date, death_full_date) <= 0) {
            isValid = false
            msg += "<li>Death date must be later than Birth date</li>"
        }

    }

    msg += "</ul>"

    if (!isValid) {
        event.preventDefault(); // Prevent form submission
        show_toast('danger', 'error', msg)
    }
});


function compareDates(birth, death) {
    var birth = new Date(birth);
    var death = new Date(death);

    return death - birth;
}

function isValidDate(dateString) {
    // First check if the string can be parsed as a date
    var date = new Date(dateString);
    if (isNaN(date.getTime())) {
        return false; // Invalid date
    }

    // Check if the parsed date matches the input string to handle cases like '2023-02-30'
    var parts = dateString.split(/[-/]/);
    var year = parseInt(parts[0], 10);
    var month = parseInt(parts[1], 10);
    var day = parseInt(parts[2], 10);

    return date.getFullYear() === year && (date.getMonth() + 1) === month && date.getDate() === day;
}

function add_child(personInfo) {

    var formAddChild = document.querySelector('#formAddChild');
    formAddChild.querySelector('.person_id').value = personInfo.personId
    formAddChild.querySelector('.person_type').value = personInfo.type
    formAddChild.querySelector('.living').checked = true;
    formAddChild.querySelector('.death-container').classList.add("d-none");

    formAddChild.querySelector('.deceased').addEventListener('change', (event) => {
        if (event.target.checked) {
            formAddChild.querySelector('.death-container').classList.remove("d-none");
        }
        else {
            formAddChild.querySelector('.death-container').classList.add("d-none");
        }
    });

    formAddChild.querySelector('.living').addEventListener('change', (event) => {
        if (event.target.checked) {
            formAddChild.querySelector('.death-container').classList.add("d-none");
        }
        else {
            formAddChild.querySelector('.death-container').classList.remove("d-none");
        }
    });

    var parents = "";
    if (personInfo.type == "spouse") {
        const person = familyData.filter((p) => p.personId === personInfo.personBeforeSpouseId);
        
        parents += `<div class="form-check">
                        <input class="form-check-input" type="radio"
                            id="defaultRadio${personInfo.name.replace(/ /g, "-")}" name="parents" value="${person[0].personId}-${personInfo.personId}" checked="true">
                        <label class="form-check-label" for="defaultRadio${personInfo.name.replace(/ /g, "-")}">
                            ${person[0].name} and ${personInfo.name}
                        </label>
                    </div>`;
    }
    else{
        console.log(personInfo.spouseIds);
        for (const spouseId of personInfo.spouseIds) {
            var spouse = familyData.filter((p) => p.spouseId === spouseId);
            spouse = spouse[0];
            parents += `<div class="form-check">
                            <input class="form-check-input" type="radio"
                                id="defaultRadio${spouse.spouseName.replace(/ /g, "-")}" name="parents" value="${personInfo.personId}-${spouse.spouseId}">
                            <label class="form-check-label" for="defaultRadio${spouse.spouseName.replace(/ /g, "-")}">
                                ${personInfo.name} and ${spouse.spouseName}
                            </label>
                        </div>`;
          }
    }

    formAddChild.querySelector('.parents_container').innerHTML = parents;

    var myOffcanvas = document.getElementById('offcanvasAddChild')
    var bsOffcanvas = new bootstrap.Offcanvas(myOffcanvas)
    bsOffcanvas.show()
}


// validate and send add spouse form
document.getElementById('formAddChild').addEventListener('submit', function (event) {
    const form = event.target;
    let isValid = true;
    let msg = "<ul>"

    // validate parents

    const parents = document.getElementsByName('parents');
    let isChecked = false;
    for (const parent of parents) {
      if (parent.checked) {
        isChecked = true;
        break;
      }
    }

    if (!isChecked) {
        isValid = false
        msg += "<li>Please select parents</li>"
    }

    // validate sex

    const sexs = document.getElementsByName('sex');
    let isCheckedSex = false;
    for (const sex of sexs) {
      if (sex.checked) {
        isCheckedSex = true;
        break;
      }
    }

    if (!isCheckedSex) {
        isValid = false
        msg += "<li>Please select sex</li>"
    }
    
    
    // validate first name
    if (form.querySelector('.firstname').value == "") {
        isValid = false
        msg += "<li>First and middle name is required</li>"
    }

    // validate last name
    if (form.querySelector('.lastname').value == "") {
        isValid = false
        msg += "<li>Last name is required</li>"
    }

    // validate status
    if (form.querySelector('.living').checked == false && form.querySelector('.deceased').checked == false) {
        isValid = false
        msg += "<li>Status must be checked</li>"
    }

    // validate birth date
    const regexFullDate = /^\d{4}-\d{2}-\d{2}$/;
    const regexYearOnly = /^\d{4}$/;

    var birth_date = form.querySelector('.birth_date').value
    if (birth_date != '') {
        if (regexFullDate.test(birth_date) || regexYearOnly.test(birth_date)) {
            if (!isValidDate(birth_date)) {
                isValid = false
                msg += "<li>Please enter a valid birth date</li>"
            }
        }
        else {
            isValid = false
            msg += "<li>Please enter a valid birth date</li>"
        }
    }


    // validate death date
    var death_date = form.querySelector('.death_date').value
    if (death_date != '') {
        if (regexFullDate.test(death_date) || regexYearOnly.test(death_date)) {
            if (!isValidDate(death_date)) {
                isValid = false
                msg += "<li>Please enter a valid death date</li>"
            }
        }
        else {
            isValid = false
            msg += "<li>Please enter a valid death date</li>"
        }
    }


    // test if death date is after birth date
    if (death_date != '' && birth_date != '') {
        var death_full_date = ''
        if (regexFullDate.test(death_date)) {
            death_full_date = death_date
        }
        if (regexYearOnly.test(death_date)) {
            death_full_date = death_date + '-01-01'
        }

        var birth_full_date = ''
        if (regexFullDate.test(birth_date)) {
            birth_full_date = birth_date
        }
        if (regexYearOnly.test(birth_date)) {
            birth_full_date = birth_date + '-01-01'
        }
        console.log(compareDates(birth_full_date, death_full_date));
        if (compareDates(birth_full_date, death_full_date) <= 0) {
            isValid = false
            msg += "<li>Death date must be later than Birth date</li>"
        }

    }

    msg += "</ul>"

    if (!isValid) {
        event.preventDefault(); // Prevent form submission
        show_toast('danger', 'error', msg)
    }
});


function add_spouse(personInfo) {

    clearForm("formAddSpouse")
    var formAddSpouse = document.querySelector('#formAddSpouse');
    formAddSpouse.querySelector('.person_id').value = personInfo.personId
    formAddSpouse.querySelector('.living').checked = true;
    formAddSpouse.querySelector('.death-container').classList.add("d-none");

    formAddSpouse.querySelector('.deceased').addEventListener('change', (event) => {
        if (event.target.checked) {
            formAddSpouse.querySelector('.death-container').classList.remove("d-none");
        }
        else {
            formAddSpouse.querySelector('.death-container').classList.add("d-none");
        }
    });

    formAddSpouse.querySelector('.living').addEventListener('change', (event) => {
        if (event.target.checked) {
            formAddSpouse.querySelector('.death-container').classList.add("d-none");
        }
        else {
            formAddSpouse.querySelector('.death-container').classList.remove("d-none");
        }
    });

    var myOffcanvas = document.getElementById('offcanvasAddSpouse')
    var bsOffcanvas = new bootstrap.Offcanvas(myOffcanvas)
    myOffcanvas.querySelector(".offcanvas-subtitle").innerHTML = "For : " + personInfo.name;
    bsOffcanvas.show()
}


// validate and send add spouse form
document.getElementById('formAddSpouse').addEventListener('submit', function (event) {
    const form = event.target;
    let isValid = true;
    let msg = "<ul>"

    // validate first name
    if (form.querySelector('.firstname').value == "") {
        isValid = false
        msg += "<li>First and middle name is required</li>"
    }

    // validate last name
    if (form.querySelector('.lastname').value == "") {
        isValid = false
        msg += "<li>Last name is required</li>"
    }

    // validate status
    if (form.querySelector('.living').checked == false && form.querySelector('.deceased').checked == false) {
        isValid = false
        msg += "<li>Status must be checked</li>"
    }

    // validate birth date
    const regexFullDate = /^\d{4}-\d{2}-\d{2}$/;
    const regexYearOnly = /^\d{4}$/;

    var birth_date = form.querySelector('.birth_date').value
    if (birth_date != '') {
        if (regexFullDate.test(birth_date) || regexYearOnly.test(birth_date)) {
            if (!isValidDate(birth_date)) {
                isValid = false
                msg += "<li>Please enter a valid birth date</li>"
            }
        }
        else {
            isValid = false
            msg += "<li>Please enter a valid birth date</li>"
        }
    }


    // validate death date
    var death_date = form.querySelector('.death_date').value
    if (death_date != '') {
        if (regexFullDate.test(death_date) || regexYearOnly.test(death_date)) {
            if (!isValidDate(death_date)) {
                isValid = false
                msg += "<li>Please enter a valid death date</li>"
            }
        }
        else {
            isValid = false
            msg += "<li>Please enter a valid death date</li>"
        }
    }


    // test if death date is after birth date
    if (death_date != '' && birth_date != '') {
        var death_full_date = ''
        if (regexFullDate.test(death_date)) {
            death_full_date = death_date
        }
        if (regexYearOnly.test(death_date)) {
            death_full_date = death_date + '-01-01'
        }

        var birth_full_date = ''
        if (regexFullDate.test(birth_date)) {
            birth_full_date = birth_date
        }
        if (regexYearOnly.test(birth_date)) {
            birth_full_date = birth_date + '-01-01'
        }
        console.log(compareDates(birth_full_date, death_full_date));
        if (compareDates(birth_full_date, death_full_date) <= 0) {
            isValid = false
            msg += "<li>Death date must be later than Birth date</li>"
        }

    }

    msg += "</ul>"

    if (!isValid) {
        event.preventDefault(); // Prevent form submission
        show_toast('danger', 'error', msg)
    }
});