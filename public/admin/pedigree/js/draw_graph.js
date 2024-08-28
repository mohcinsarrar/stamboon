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

            let nodeHtml = `<div class="${extraCss}" 
            style="
                display: flex;
                flex-wrap: wrap;
                margin-top: 0;
                margin-left: 0 !important;
                margin-right: 0 !important;
                box-sizing: border-box;
            ">`;
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
                        x = d.x - d.width / 2;
                    } else {
                        x = d.x + d.width / 2;
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

    
    // set background from settings
    set_background()

    

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
    // apply background from settings
    /*
    
    */
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

    // init portrait
    if (person.photo !== undefined && person.photo !== null) {
        personIcon = "/storage/portraits/" + person.photo;
    }
    else{
        if (person.gender === 'M') {
            personIcon = maleIcon;
        } else {
            personIcon = femaleIcon;
        }
    }

    // init box color
    /// if color box type is gender
    if(treeConfiguration.boxColor == "gender"){
        if (person.gender === 'M') {
            personCssClass = `background-color: ${treeConfiguration.maleColor} !important;`;
        } else {
            personCssClass = `background-color: ${treeConfiguration.femaleColor} !important; `;
        }
    }
    /// if color box type is blood relative
    else{
        if (personType === 'spouse') {
            personCssClass = `background-color: ${treeConfiguration.notbloodColor} !important;`;
        } else {
            if(personData.adopted == true){
                personCssClass = `background-color: ${treeConfiguration.notbloodColor} !important;`;
            }
            else{
                personCssClass = `background-color: ${treeConfiguration.bloodColor} !important;`;
            }
        }
    }

    // init box color
    /// if color box type is gender
    
    if(treeConfiguration.textColor == "gender"){
        if (person.gender === 'M') {
            textColor = `color: ${treeConfiguration.maleTextColor} !important;`;
        } else {
            textColor = `color: ${treeConfiguration.femaleTextColor} !important;`;
        }
    }
    /// if color box type is blood relative
    else{
        if (personType === 'spouse') {
            textColor = `color: ${treeConfiguration.notbloodTextColor} !important;`;
        } else {
            if(personData.adopted == true){
                textColor = `color: ${treeConfiguration.notbloodTextColor} !important;`;
            }
            else{
                textColor = `color: ${treeConfiguration.bloodTextColor} !important;`;
            }
        }
    }
    

    
    // add line between person and spouse if exist according to the template
    let nodeContent = '';
    if (
        personData.spouseId !== undefined &&
        personType === 'spouse' &&
        personData.primarySpouseId === undefined
    ) {
        if(treeConfiguration.nodeTemplate == "1"){
            nodeContent += `
                            <div class="" 
                            style="
                                box-sizing: border-box;
                                border-color :${treeConfiguration.connectionStroke};
                                margin-top: 34px;
                                width: 0px;
                                border-style: solid;
                                border-width: 2px 0 0;
                                cursor: auto;
                            ">
                                <hr 
                                style="
                                    box-sizing: border-box;
                                    border-style: solid;
                                    border-width: 5px 0 0;
                                "/>
                            </div>`;
        }
        if(treeConfiguration.nodeTemplate == "2"){
            nodeContent += `
                            <div class="" 
                            style="
                                box-sizing: border-box;
                                border-color :${treeConfiguration.connectionStroke};
                                margin-top: 89px;
                                width: 0px;
                                border-style: solid;
                                border-width: 2px 0 0;
                                cursor: auto;
                            ">
                                <hr 
                                />
                            </div>`;
        }
        if(treeConfiguration.nodeTemplate == "3"){
            nodeContent += `
                            <div class="" 
                            style="
                                box-sizing: border-box;
                                border-color :${treeConfiguration.connectionStroke};
                                margin-top: 69px;
                                width: 2px;
                                border-style: solid;
                                border-width: 2px 0 0;
                                cursor: auto;
                            ">
                                <hr 
                                />
                            </div>`;
        }
        if(treeConfiguration.nodeTemplate == "4"){
            nodeContent += `
                            <div class="" 
                            style="
                                box-sizing: border-box;
                                border-color :${treeConfiguration.connectionStroke};
                                margin-top: 64px;
                                width: 0px;
                                border-style: solid;
                                border-width: 2px 0 0;
                                cursor: auto;
                                padding: 0;
                            ">
                                <hr 
                                />
                            </div>`;
        }
        else{
            nodeContent += `<div class="line" style="border-color :${treeConfiguration.connectionStroke}"><hr/></div>`;
        }
        
    }


    let drillToHide = 'hide';
    if (personType === 'spouse' && personData.spouseDrillTo) {
        drillToHide = ''; // hide drill to icon
    }

    if(treeConfiguration.nodeTemplate == "1"){
        nodeContent += `
          <div class="person-${person.personId}" 
          style="
            /* col */
            flex: 1 0 0%;
            /* px-0 */
            padding-left: 0 !important;
            padding-right: 0 !important;
            /* style */
            display: flex;
            flex-wrap: wrap;
            height: 70px;
            align-items: center;
            justify-content: center;
            box-sizing: border-box;
          "
          >
            
            <div class="" onClick="window.personClicked='${person.personId}';" data-personId="${person.personId}"
            style="
                ${personCssClass};
                box-sizing: border-box;
                /* card */
                word-wrap: break-word;
                border: 0 solid #dbdade;
                display: flex;
                flex-direction: column;
                min-width: 0;
                position: relative;
                /* rounded-0 */
                border-radius: 0 !important;
                /* w-100 */
                width: 100% !important;
                /* overflow-hidden */
                overflow: hidden !important;
                /* style */
                height: 70px !important;
                position: relative;
            "
            >
                <div class="" 
                style="
                    box-sizing: border-box;
                    /*row*/
                    display: flex;
                    flex-wrap: wrap;
                    margin-top: 0;
                    /* mx-0 */
                    margin-left: 0 !important;
                    margin-right: 0 !important;
                    /* overflow-hidden */
                    overflow: hidden !important;
                    /* h-100 */
                    height: 100% !important;
                ">
                <div class="" 
                    style="
                        box-sizing: border-box;
                        max-height:70px;
                        /* col-4 */
                        width: 33.33333333%;
                    ">
                    <img class="" src="${personIcon}" alt="${person.personName}" 
                    style="
                        vertical-align: middle;
                        box-sizing: border-box;
                        object-fit: cover;
                        /* card-img */
                        width: 100%;
                        /* rounded-0 */
                        border-radius: 0 !important;
                        /* h-100 */
                        height: 100% !important;
                    ">
                </div>
                <div class="" 
                style="
                    box-sizing: border-box;
                    max-height:70px;
                    /* col-8 */
                    width: 66.66666667%;
                    /* p-1 */
                    padding: .25rem !important;
                    /* d-flex */
                    display: flex !important;
                    /* align-items-center */
                    align-items: center !important;
                ">
                    <div class="" 
                    style="
                        box-sizing: border-box;
                        ${textColor};
                        /* card-body */
                        flex: 1 1 auto;
                        /* p-1 */
                        padding: .25rem !important;
                        /* ms-1 */
                        margin-left: .25rem !important;
                    ">
                    <p class="" 
                    style="
                        box-sizing: border-box;
                        margin-top: 0;
                        /* mb-1 */
                        margin-bottom: .25rem !important;
                        /* fw-bold */
                        font-weight: 700 !important;
                        /* style */
                        display: -webkit-box;
                        -webkit-line-clamp: 2;
                        -webkit-box-orient: vertical;
                        overflow: hidden;
                        text-overflow: ellipsis;
                    ">${person.personName}</p>
                    <p class="" 
                    style="
                        box-sizing: border-box;
                        margin-bottom: 0;
                        margin-top: 0;
                    "><small class="" 
                    style="
                        font-size: .8125rem;
                    ">
                    ${getFullDate(person.birth, person.death)}</small></p>
                    </div>
                </div>
                </div>
            </div>
          </div>`;
    }

    if(treeConfiguration.nodeTemplate == "2"){
        nodeContent += `
        <div class="person-${person.personId}" 
        style="
            /* col */
            flex: 1 0 0%;
            /* px-0 */
            padding-left: 0 !important;
            padding-right: 0 !important;
            /* style */
            display: flex;
            flex-wrap: wrap;
            height: 180px;
            align-items: center;
            justify-content: center;
            box-sizing: border-box;
        ">
            <div class="" onClick="window.personClicked='${person.personId}';" data-personId="${person.personId}"
            style="
                ${personCssClass};
                box-sizing: border-box;
                /* card */
                background-clip: padding-box;
                box-shadow: 0 .25rem 1.125rem rgba(75,70,92,.1);
                word-wrap: break-word;
                border: 0 solid #dbdade;
                border-radius: 0.375rem;
                display: flex;
                flex-direction: column;
                min-width: 0;
                /* rounded-0 */
                border-radius: 0 !important;
                /* w-100 */
                width: 100% !important;
                /* overflow-hidden */
                overflow: hidden !important;
                /* py-3 */
                padding-bottom: 1rem !important;
                padding-top: 1rem !important;
                /* px-2 */
                padding-left: .5rem !important;
                padding-right: .5rem !important;
                /* text-center*/
                text-align: center !important;
                /* style */
                height: 180px !important;
                position: relative;
            "
            >
                <div class="" style="margin-bottom: 1rem !important; box-sizing: border-box;"> 
                <img src="${personIcon}" alt="${person.personName}" class="" 
                style="
                    box-sizing: border-box;
                    /* style */
                    height: 100px;
                    width: 100px;
                    object-fit: cover;
                    vertical-align: middle;
                    /* rounded-circle */
                    border-radius: 50% !important;
                "
                > 
                </div>
                <div style="${textColor}; box-sizing: border-box;">
                    <p class=""
                    style="
                        /* fw-bold */
                        font-weight: 700 !important;
                        /* mb-2 */
                        margin-bottom: .5rem !important;
                        margin-top: 0;
                        box-sizing: border-box;
                    "
                    >${truncateText(person.personName,20)}
                    </p> 
                    <small style="font-size: .8125rem;">${getFullDate(person.birth, person.death)}</small>
                </div>
            </div>
        </div>
        `;
    }
    
    if(treeConfiguration.nodeTemplate == "3"){
        
        nodeContent += `
        <div class="person-${person.personId}" 
        style="
            /* col */
            flex: 1 0 0%;
            /* px-0 */
            padding-left: 0 !important;
            padding-right: 0 !important;
            /* style */
            display: flex;
            flex-wrap: wrap;
            height: 140px;
            align-items: center;
            justify-content: center;
            box-sizing: border-box;
        "
        >
            <div class="" onClick="window.personClicked='${person.personId}';" data-personId="${person.personId}"
            style="
            ${personCssClass};
            /* style */
            height: 140px !important;
            position: relative;
            width: 100%;
            border-radius: 5px;
            border: none;
            /* p-2 */
            padding: .5rem !important;
            /* align-item-center */
            align-items: center !important;
            justify-content: center !important;
            display: flex !important;
            /* card */
            background-clip: padding-box;
            box-shadow: 0 .25rem 1.125rem rgba(75,70,92,.1);
            word-wrap: break-word;
            flex-direction: column;
            min-width: 0;
            box-sizing: border-box;
            "
            >
            
                <div style="width: 100% !important; box-sizing: border-box;">
                <img src="${personIcon}" alt="${person.personName}" class="" 
                style="
                    /* style */
                    height: 100px;
                    width: 100px;
                    object-fit: cover;
                    border: 4px solid #eee;
                    position: absolute;
                    left: 50%;
                    top: 0;
                    transform: translate(-50%, -50%);
                    /* rounded-circle */
                    border-radius: 50% !important;
                    box-sizing: border-box;
                "
                >
                </div>
                <div class="" 
                style="
                    ${textColor};
                    /* text-center */
                    text-align: center !important;
                    /* style */
                    font-size: 20px;
                    margin-top: 55px;
                    box-sizing: border-box;
                "
                >
                    <p style="margin-bottom: .5rem !important; margin-top: 0; box-sizing: border-box;">
                    ${truncateText(person.personName,15)}
                    </p>
                    <p 
                    style="
                        font-size: 12px;
                        font-weight: 700;
                        margin-bottom: 0 !important;
                        margin-top: 0;
                        box-sizing: border-box;
                    "
                    >${getFullDate(person.birth, person.death)}</p>
                </div>
            </div>
        </div>
        `;
    }
    
    if(treeConfiguration.nodeTemplate == "4"){
        
        nodeContent += `
        <div class="person-member person-${person.personId}" 
        style="
            /* col */
            flex: 1 0 0%;
            /* px-0 */
            padding-left: 0 !important;
            padding-right: 0 !important;
            /* style */
            display: flex;
            flex-wrap: wrap;
            height: 130px;
            align-items: center;
            justify-content: center;
            box-sizing: border-box;
        "
        >
            <div class="card" onClick="window.personClicked='${person.personId}';" data-personId="${person.personId}"
            style="
            /* style*/
            height: 130px !important;
            position: relative;
            width: 100%;
            border-radius: 5px;
            border: none;
            background-color: transparent;
            box-shadow: none;
            /* card */
            background-clip: padding-box;
            word-wrap: break-word;
            display: flex;
            flex-direction: column;
            min-width: 0;
            box-sizing: border-box;
            "
            >
                <img src="${personIcon}" alt="${person.personName}" class=""
                style="
                    /* style */
                    position: absolute;
                    left: 50%;
                    top: 0;
                    transform: translate(-50%, -50%);
                    height: 100px;
                    width: 100px;
                    object-fit: cover;
                    border: 4px solid #eee;
                    /* rounded */
                    border-radius: 50% !important;
                    box-sizing: border-box;
                ">
                <div class="" 
                style="
                    /* style */
                    margin-top: 50px;
                    height: 100% !important;
                    /* row */
                    display: flex;
                    flex-wrap: wrap;
                    margin-left: calc(1.5rem*-.5);
                    margin-right: calc(1.5rem*-.5);
                    box-sizing: border-box;
                "
                >
                    <div 
                    style="
                        flex: 1 0 0%;
                        margin-top: var(--bs-gutter-y);
                        max-width: 100%;
                        padding-left: calc(1.5rem*.5);
                        padding-right: calc(1.5rem*.5);
                        width: 100%;
                        box-sizing: border-box;
                    ">
                        <div class="h-100 text-center py-3 px-2" 
                        style="
                            ${personCssClass};
                            text-align: center !important;
                            padding-bottom: 1rem !important;
                            padding-top: 1rem !important;
                            padding-left: .5rem !important;
                            padding-right: .5rem !important;
                            height: 100% !important;
                            box-sizing: border-box;

                        ">
                            <h6 class="mb-2" 
                            style="
                                ${textColor}; 
                                margin-bottom: .5rem !important; 
                                font-size: .9375rem;
                                font-weight: 600;
                                line-height: 1.37;
                                margin-top: 0;
                                box-sizing: border-box;
                            "
                            >
                            ${truncateText(person.personName,15)}</h6>
                            <p class="mb-0" 
                            style="
                                ${textColor};
                                margin-bottom: 0 !important;
                                margin-top: 0;
                                box-sizing: border-box;
                            "
                            >
                            ${getFullDate(person.birth, person.death)}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        `;
    }
    
    return nodeContent;
    
}


function truncateText(text, maxLength) {
    if (text.length <= maxLength) {
        return text;
    }
    return text.substring(0, maxLength) + '...';
}

function is_death(status,template) {
    if(status != "Deceased"){
        return "";
    }


    if (template == "1") {
        return `
            <div class="diagonal-text">
                <span></span>
            </div>
        `;
    }
    if (template == "2") {
        return `<div class="diagonal-text"><span></span></div>`;
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
















