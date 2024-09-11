// clear forms
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


// close custom modal when click on close button
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
    var offcanvasElement = document.getElementById('modalEditImage');
    if (offcanvasElement.classList.contains('show')) {
        return;
    }
    const modal = document.getElementById('nodeModal');
    modal.style.display = 'none';
}


// compare tow string dates
function compareDates(birth, death) {
    var birth = new Date(birth);
    var death = new Date(death);

    return death - birth;
}

//check if is a string date is valid

function isValidDate(dateString) {

    // Handle case for year only (YYYY)
    if (/^\d{4}$/.test(dateString)) {
        return true; // Valid year
    }

    if (/^\d{4}-\d{2}-\d{2}$/.test(dateString)) {
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

    return false

}


// set graph background from settings

function set_background() {

    const imageUrl = "/admin/images/Parchment_small" + treeConfiguration.bgTemplate + ".png";
    convertImageToBase64(imageUrl, function (base64String) {
        const replaced = base64String.replace(/(\r\n|\n|\r)/gm);
        d3.select('.svg-chart-container')
            .style(
                'background',
                '#ffffff'
            )
            .style(
                'background-image',
                `url(${replaced}), radial-gradient(circle at center, rgba(255,0,0,0) 0, rgba(255,0,0,0) 100%)`
            )
            .style(
                'background-size',
                '100% 99%'
            ).style(
                'background-repeat',
                'no-repeat'
            );
    });

}


function convertImageToBase64(imageUrl, callback) {
    fetch(imageUrl)
        .then(response => response.blob())
        .then(blob => {
            const reader = new FileReader();
            reader.onloadend = function () {
                // Base64 string result
                const base64String = reader.result;
                callback(base64String); // Send the base64 string to the callback
            };
            reader.readAsDataURL(blob);
        })
        .catch(error => {
            console.error("Error converting image to Base64:", error);
        });
}


function editChartStatus() {

    const nodes = chart.getChartState().allNodes;

    const nodeStatuses = nodes.map(node => ({
        id: node.data.id,
        expanded: node.data._expanded // or use the actual property name
    }));

    var chart_status = {
        'expand': nodeStatuses,
    }

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        url: "/pedigree/editchartstatus",
        type: 'POST',
        data: {
            'chart_status': chart_status,
        },
        dataType: 'json',
        success: function (data) {
            if (data.error == false) {
            } else {
                show_toast('danger', 'error', "can't store chart status, please try again !")
                return null;
            }

        },
        error: function (xhr, status, error) {
            show_toast('danger', 'error', "can't store chart status please try again !")
            return null;
        }
    });

}


function applyChartStatus() {



    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        url: '/pedigree/getchartstatus',
        method: 'GET',
        success: function (data) {
            if (data.error == false) {

                // get chart status
                const chart_status = data.chart_status;
                if(chart_status == null){
                    return;
                }
                const getChartState = chart.getChartState()

                // apply expand
                const nodes = getChartState.allNodes;
                nodes.forEach(node => {
                    const status = chart_status['expand'].find(s => s.id === node.data.id);
                    if (status) {
                        if (status.expanded == "true") {

                            chart.setExpanded(node.data.id)
                        }
                        else {
                            chart.setExpanded(node.data.id, false)
                        }
                    }

                });
                chart.render().fit();

            } else {
                show_toast('danger', 'error', "can't get chart status, please try again !")
                return null;
            }

        },
        error: function (xhr) {
            show_toast('danger', 'error', "can't get chart status please try again !")
            return null;
        }
    });




}

function extract_cord(pos) {
    const regex = /translate\((-?\d+(\.\d+)?),\s*(-?\d+(\.\d+)?)\)/;
    const x = parseFloat(pos.match(regex)[1]);
    const y = parseFloat(pos.match(regex)[3]);

    return {
        "x": x,
        "y": y
    }
}

function update_path_position(position, x) {
    return position.replace(/L-?\d+(\.\d+)?,/, `L${x},`);
}

function change_node_position(staticNodeId, changedNodeId, index) {

    // After rendering, manually adjust the position of the two specific nodes
    const staticNode = d3.select(`[data-nodeId="${staticNodeId}"]`); // Replace `node_id_1` with the actual node ID or class
    const changedNode = d3.select(`[data-nodeId="${changedNodeId}"]`); // Replace `node_id_2` with the actual node ID or class
    if (staticNode.node() == null || changedNode.node() == null) {
        return false;
    }

    // Get current positions (x and y) of the two nodes

    const staticNodeCord = extract_cord(staticNode.attr('transform'))
    const changedNodeCord = extract_cord(changedNode.attr('transform'))

    // Adjust the margin by changing the x position of the second node
    const customMargin = (treeConfiguration.nodeWidthSpouse + 25) + ((treeConfiguration.nodeWidth + 25) * (index - 1)) ; // Set a custom margin between these two nodes
    console.log(changedNodeId,customMargin)
    changedNode.attr('transform', `translate(${staticNodeCord.x + customMargin}, ${changedNodeCord.y})`); // Adjust x position only

    const newnode1cord = extract_cord(changedNode.attr('transform'))
    /////////////////
    const path = d3.select(`[data-from="${staticNodeId}"][data-to="${changedNodeId}"]`);

    path.attr("d", update_path_position(path.attr("d"), newnode1cord.x))

}


function apply_change_node_position() {

    if(chart == undefined){
        return false;
    }
    
    allNodes = chart.getChartState().allNodes
    console.log(allNodes)
    allNodes.forEach((node) => {
        // the node has no children
        if (node.children == undefined) {
            // the node is a spouse
            if (node.data.primarySpouseId != undefined) {
                // is not the primary spouse
                if (node.data.id != node.data.primarySpouseId) {
                    const staticNodeId = node.data.primarySpouseId
                    const changedNodeId = node.data.id
                    const index = node.data.spouseIds.indexOf(node.data.spouseId);
                    change_node_position(staticNodeId, changedNodeId,index)
                }
            }
        }
    });
}