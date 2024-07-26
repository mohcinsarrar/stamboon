// init variables //
const maleIcon = '/storage/portraits/male2.gif';
const femaleIcon = '/storage/portraits/female2.gif';
var chart;
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
    chart = new d3.OrgChart()
        .container(treeConfiguration.chartContainer)
        .data(data)
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

    // getting the selectedPerons to show
    const selectedPersons = treeData.filter(
        (p) => p.personId === selectedPersonId
    );
    if (selectedPersons.length > 0) {
        const nodeId = selectedPersons[0].id;
        chart.setCentered(nodeId);
    }
    chart.expandAll();

    // hiding the root node
    /*
    const rootIdNode = document.querySelector(
        '.person-' + constants.rootPersonId
    );
    const parentRootIdNode = rootIdNode?.closest('.node');
    if (parentRootIdNode !== undefined && parentRootIdNode !== null)
        parentRootIdNode.style.display = 'none';
    */
}

// generate NodeContent
function getPersonNodeContent(personData, personType) {
    const person = {};

    if (personType === 'spouse') {
        if (personData.spouseId !== undefined) {
            person.personId = personData.spouseId;
        } else {
            return '';
        }
        person.personName = personData.spouseLabel1;
        person.gender = personData.spouseGender;
        person.photo = personData.spousePhoto;
    } else {
        person.personId = personData.personId;
        person.personName = personData.label1;
        person.gender = personData.gender;
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
        adoptiveChild = 'border-start border-danger border-start-3'
    }
    
    nodeContent += `
          <div class="col px-0 person-member person-${person.personId} ${selectedPersonCssClass}">
            
            <div class="card rounded-0 w-100 overflow-hidden ${personCssClass} ${adoptiveChild}" onClick="window.personClicked='${person.personId}';">
                ${is_death(personData.death)}
                <div class="row g-0 mx-0 overflow-hidden">
                <div class="col-4">
                    <img class="card-img card-img-left rounded-0 object-fit-cover" src="${personIcon}" alt="Card image">
                </div>
                <div class="col-8 align-items-center d-flex">
                    <div class="card-body p-1 ms-1">
                    <p class="card-title mb-1 ellipsis">${person.personName}</p>
                    <p class="card-text"><small class="">${getParsedDate(personData.birth,personData.death)}</small></p>
                    </div>
                </div>
                </div>
            </div>
          </div>`;
    return nodeContent;
    //}
}

function is_death(death){
    if(death == null){
        return `<div class="diagonal-text"><span></span></div>`;
    }
    else{
        return "";
    }
}

function parseDate(dateStr){

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


function getParsedDate(birth,death) {
    let parsedDate = "";

    if(birth != null){
        birthDate = parseDate(birth).getFullYear();
    }
    else{
        birthDate = '';
    }

    if(death != null){
        deathDate = parseDate(death).getFullYear();
    }
    else{
        deathDate = '';
    }

    if(birthDate == '' && deathDate == ''){
        return ''
    }

    return `(${birthDate}-${deathDate})`


}
function nodeClicked(id) {
    console.log(window.personClicked)
}



function draw_graph(treeData) {
    renderChart(treeData)
}
