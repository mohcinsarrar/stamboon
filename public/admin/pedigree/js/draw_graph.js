// render chart //
function renderChart() {
    d3.select(treeConfiguration.chartContainer).selectAll("*").remove();
    var data = familyData;
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
                if (d.data.adopted == true) {
                    d3.select(this)
                        .attr('stroke', () => treeConfiguration.linkStrokeAdopted)
                        .attr('stroke-width', () => treeConfiguration.linkStrokeWidth);
                }
                else {
                    d3.select(this)
                        .attr('stroke', () => treeConfiguration.linkStroke)
                        .attr('stroke-width', () => treeConfiguration.linkStrokeWidth);
                }

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
            extraCss = `template-${treeConfiguration.nodeTemplate}`

            let nodeHtml = `<div class="row mx-0 ${extraCss}">`;
            if (personData.primarySpouseId !== undefined) {
                // additional spouses
                nodeHtml += getPersonNodeContent(personData, 'spouse', treeConfiguration);
            } else if (personData.gender === 'F') {
                nodeHtml += getPersonNodeContent(personData, 'person', treeConfiguration);
                nodeHtml += getPersonNodeContent(personData, 'spouse', treeConfiguration);

            } else {
                nodeHtml += getPersonNodeContent(personData, 'person', treeConfiguration);
                nodeHtml += getPersonNodeContent(personData, 'spouse', treeConfiguration);
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
                        x = d.x - d.width / 2;
                    } else {
                        x = d.x - d.width / 2;
                    }
                }
            });
        } else if (d.data.spouseId !== undefined && d.data.gender === 'M') {
            x = d.x - treeConfiguration.linkShift; // for parent to child link
        } else if (d.data.spouseId !== undefined && d.data.gender === 'F') {
            x = d.x - treeConfiguration.linkShift; // for parent to child link
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

    chart.render().fit();

    

    // change connections order (lower)
    const chartElement = document.querySelector('.center-group');
    const thirdGroup = document.querySelector('.connections-wrapper');

    // Move the third group to the first position
    chartElement.insertBefore(thirdGroup, chartElement.firstChild);


    // add tools zoom, expand ...
    $(document).on("click", "#fit", function () {
        chart.fit()
    });

    $(document).on("click", "#zoomIn", function () {
        
        chart.zoomIn()
        console.log(chart.getChartState());
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

    let personCssClass, personIcon, textColor;
    let photoClass = '';
    if (person.gender === 'M') {
        personCssClass = `background-color: ${treeConfiguration.maleColor} !important;`;
        textColor = `color: ${treeConfiguration.maleTextColor} !important;`;
        personIcon = maleIcon;
    } else {
        personCssClass = `background-color: ${treeConfiguration.femaleColor} !important; `;
        textColor = `color: ${treeConfiguration.femaleTextColor} !important;`;
        personIcon = femaleIcon;
    }

    if (person.photo !== undefined && person.photo !== null) {
        personIcon = "/storage/portraits/" + person.photo;
        photoClass = 'profile-photo';
    }

    let nodeContent = '';
    if (
        personData.spouseId !== undefined &&
        personType === 'spouse' &&
        personData.primarySpouseId === undefined
    ) {
        nodeContent += `<div class="line" style="border-color :${treeConfiguration.connectionStroke}"><hr/></div>`;
    }


    let drillToHide = 'hide';
    if (personType === 'spouse' && personData.spouseDrillTo) {
        drillToHide = ''; // hide drill to icon
    }

    if(treeConfiguration.nodeTemplate == "1"){
        nodeContent += `
          <div class="col px-0 person-member person-${person.personId} ">
            
            <div class="card rounded-0 w-100 overflow-hidden" style="${personCssClass}" onClick="window.personClicked='${person.personId}';" data-personId="${person.personId}">
                ${is_death(person.status)}
                <div class="row g-0 mx-0 overflow-hidden h-100">
                <div class="col-4" style="max-height:70px;">
                    <img class="card-img card-img-left rounded-0 h-100" src="${personIcon}" alt="${person.personName}" style="object-fit: cover;">
                </div>
                <div class="col-8 p-1 d-flex align-items-center" style="max-height:70px;">
                    <div class="card-body p-1 ms-1" style="${textColor}">
                    <p class="card-title mb-1 ellipsis fw-bold">${person.personName}</p>
                    <p class="card-text"><small class="">${getFullDate(person.birth, person.death)}</small></p>
                    </div>
                </div>
                </div>
            </div>
          </div>`;
    }

    if(treeConfiguration.nodeTemplate == "2"){
        nodeContent += `
        <div class="col px-0 person-member person-${person.personId} ">
            <div class="card p-2 py-3 text-center w-100 overflow-hidden" style="${personCssClass}" onClick="window.personClicked='${person.personId}';" data-personId="${person.personId}">
                ${is_death(person.status)}
                <div class="mb-3"> <img src="${personIcon}" alt="${person.personName}" class="rounded-circle"> </div>
                <div style="${textColor}">
                    <p class="mb-2 fw-bold">${truncateText(person.personName,20)}</p> 
                    <small>${getFullDate(person.birth, person.death)}</small>
                </div>
            </div>
        </div>
        `;
    }

    if(treeConfiguration.nodeTemplate == "3"){
        
        nodeContent += `
        <div class="col px-0 person-member person-${person.personId} ">
            <div class="card p-2 bg-white d-flex align-items-center justify-content-center" style="${personCssClass}" onClick="window.personClicked='${person.personId}';" data-personId="${person.personId}">
            
                <div class="w-100">
                <img src="${personIcon}" alt="${person.personName}" class="rounded-circle">
                </div>
                <div class="text-center info" style="${textColor}">
                    <p class="name mb-2">${truncateText(person.personName,15)}</p>
                    <p class="job mb-0">${getFullDate(person.birth, person.death)}</p>
                </div>
            </div>
        </div>
        `;
    }
    
    return nodeContent;
    //}
}


function truncateText(text, maxLength) {
    if (text.length <= maxLength) {
        return text;
    }
    return text.substring(0, maxLength) + '...';
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
    var modalElement = document.getElementById('modalEditImage');
    if (modalElement.classList.contains('show')) {
        return;
    }

    const person = familyData.filter((p) => p.personId === window.personClicked);
    var personInfo = {}
    if (person.length > 0) {
        personInfo.personId = person[0].personId
        personInfo.name = person[0].name
        personInfo.sex = person[0].gender
        personInfo.photo = person[0].photo
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
        personInfo.sex = spouse[0].spouseGender
        personInfo.status = spouse[0].spouseStatus
        personInfo.photo = spouse[0].spousePhoto
        personInfo.birth = spouse[0].spouseBirth
        personInfo.death = spouse[0].spouseDeath
        personInfo.type = 'spouse'
        personInfo.personBeforeSpouseId = spouse[0].personId
    }

    // store on global variable
    selectedPerson = personInfo

    // insert person info in nodeModal
    const modal = document.getElementById('nodeModal');
    const modalBody = document.getElementById('nodeModalBody');

    modalBody.querySelector('.name').innerHTML = personInfo.name;

    modalBody.querySelector('.birth').innerHTML = personInfo.birth;
    modalBody.querySelector('.death').innerHTML = personInfo.death;
    if (personInfo.photo != undefined) {
        modalBody.querySelector('.personImage').src = "/storage/portraits/" + personInfo.photo;
    }
    else {
        if (personInfo.sex == 'M') {
            modalBody.querySelector('.personImage').src = maleIcon;
        }
        else {
            modalBody.querySelector('.personImage').src = femaleIcon;
        }
    }


    // Get the node's position

    const node = document.querySelector('[data-personId="' + window.personClicked + '"]').getBoundingClientRect();


    // Position the modal
    modal.style.left = `${node.left}px`;
    modal.style.top = `${node.top}px`;

    // Show the modal
    modal.style.display = 'block';
}



// buttons click
document.getElementById('nodeModalBody').querySelector('#nodeEdit').addEventListener('click', (event) => {
    edit_person()
});

document.getElementById('nodeModalBody').querySelector('#nodeEditPhoto').addEventListener('click', (event) => {
    edit_image()
});

document.getElementById('nodeModalBody').querySelector('#nodeDelete').addEventListener('click', (event) => {
    delete_person()
});
document.getElementById('nodeModalBody').querySelector('#addSpouse').addEventListener('click', (event) => {
    add_spouse()
});
document.getElementById('nodeModalBody').querySelector('#addChild').addEventListener('click', (event) => {
    add_child()
});
















