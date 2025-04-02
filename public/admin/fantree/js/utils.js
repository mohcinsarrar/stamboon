function get_fantree_id(){
    let fantree_id = document.getElementById('main_graph').dataset.fantreeid;
    return(fantree_id);
}

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


function load_settings(){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    let fantree_id = get_fantree_id()
    $.ajax({
        url: "/fantree/settings/"+fantree_id,
        type: 'GET',
        dataType: 'json',
        success: function (data) {
            if (data.error == false) {

                treeConfiguration = data.settings

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
}


// close custom modal when click on close button
function close_custom_modal() {
    
    if(preventOverlayModel()){
        return;
    }
        
    const modal = document.getElementById('nodeModal');
    modal.style.display = 'none';
}

function preventOverlayModel(){

    var offcanvasElement = document.getElementById('offcanvasAddParents');
    if (offcanvasElement.classList.contains('show')) {
        return true;
    }
    var offcanvasElement = document.getElementById('offcanvasAddPerson');
    if (offcanvasElement.classList.contains('show')) {
        return true;
    }
    var offcanvasElement = document.getElementById('modalEditImage');
    if (offcanvasElement.classList.contains('show')) {
        return true;
    }
    var offcanvasElement = document.getElementById('previewImage');
    if (offcanvasElement.classList.contains('show')) {
        return true;
    }
    var offcanvasElement = document.getElementById('offcanvasUpdatePerson');
    if (offcanvasElement.classList.contains('show')) {
        return true;
    }
    var offcanvasElement = document.getElementById('exportModal');
    if (offcanvasElement.classList.contains('show')) {
        return true;
    }
    var offcanvasElement = document.getElementById('settings');
    if (offcanvasElement.classList.contains('show')) {
        return true;
    }
    
    
    return false;
}



// compare tow string dates
function compareDates(birth, death) {
    var birth = new Date(birth);
    var death = new Date(death);

    return death - birth;
}


function person_parent_length(personInfo){
    var count = 0;
    personInfo.parents.forEach(parent => {
        if(parent.id != undefined){
            count++;
        }
        
    });

    return count;
}




// set graph background from settings

function set_background() {
    
    if(treeConfiguration.bg_template != '0'){
        const imageUrl = "/admin/images/Parchment_small" + treeConfiguration.bg_template + ".png";
        convertImageToBase64(imageUrl, function (base64String) {
            const replaced = base64String.replace(/(\r\n|\n|\r)/gm);
            d3.select('#svg-container')
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

    const transformString = chart.select('#chartGroup').attr('transform')

    // Regular expressions to match the translate and scale values
    const translateMatch = transformString.match(/translate\(([^,]+),([^)]+)\)/);
    const scaleMatch = transformString.match(/scale\(([^)]+)\)/);

    // Extract values if matches are found
    let translateX = null
    let translateY = null
    let scale = null
    if (translateMatch) {
        translateX = parseFloat(translateMatch[1]);
        translateY = parseFloat(translateMatch[2]);
    }

    if (scaleMatch) {
        scale = parseFloat(scaleMatch[1]);
    }

    if(translateX != null && translateY != null && scale != null){
        const chart_status = {
            'translateX' : translateX,
            'translateY' : translateY,
            'scale' : scale,

        }
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        let fantree_id = get_fantree_id()
        $.ajax({
            url: "/fantree/editchartstatus/"+fantree_id,
            type: 'POST',
            data: {
                'chart_status': chart_status
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


    

}


function applyChartStatus() {



    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    let fantree_id = get_fantree_id()
    $.ajax({
        url: '/fantree/getchartstatus/'+fantree_id,
        method: 'GET',
        success: function (data) {
            if (data.error == false) {

                chart_status = data.chart_status

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
    const changedNode = d3.select(`[data-nodeId="${changedNodeId}"]`); // Replace `node_id_2` with the actual node ID or classc

    if (staticNode.node() == null || changedNode.node() == null) {
        return false;
    }

    // Get current positions (x and y) of the two nodes

    const staticNodeCord = extract_cord(staticNode.attr('transform'))
    const changedNodeCord = extract_cord(changedNode.attr('transform'))

    // Adjust the margin by changing the x position of the second node
    const customMargin = (treeConfiguration.nodeWidthSpouse + 25) + ((treeConfiguration.nodeWidth + 25) * (index - 1)); // Set a custom margin between these two nodes
    changedNode.attr('transform', `translate(${staticNodeCord.x + customMargin}, ${changedNodeCord.y})`); // Adjust x position only

    const newnode1cord = extract_cord(changedNode.attr('transform'))
    /////////////////
    const path = d3.select(`[data-from="${staticNodeId}"][data-to="${changedNodeId}"]`);

    path.attr("d", update_path_position(path.attr("d"), newnode1cord.x));

}


function apply_change_node_position() {

    if (chart == undefined) {
        return false;
    }

    if (compact == true) {
        return false;
    }

    allNodes = chart.getChartState().allNodes
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
                    change_node_position(staticNodeId, changedNodeId, index)
                }
            }
        }
    });

    compat = true
}



function test_all_max_nodes() {
    if (chart == undefined) {
        return false;
    }

    nodes = chart.selectAll(".node").data()
    if (nodes == undefined || nodes == []) {
        return false;
    }

    const nodes_count = nodes.filter(obj => !obj.data || obj.data.id != undefined).length;

    if (nodes_count > treeConfiguration.max_nodes) {
        document.querySelector('#max-node-alert').style.display = "block";
        document.querySelector('#max-node-alert div.alert').innerHTML = "You have reached the max nodes available (" + treeConfiguration.max_nodes + ")";
        return false;
    }
    else{
        if(document.querySelector('#max-node-alert')){
            document.querySelector('#max-node-alert').style.display = "none";
        }
        return true;
    }

}

function disable_tools_bar() {
    const div = document.querySelector('#tools-bar');

    // Disable all buttons inside the div
    div.querySelectorAll('button').forEach(button => {
        button.disabled = true;
    });

    // Disable all href elements inside the div
    div.querySelectorAll('a').forEach(anchor => {
        anchor.addEventListener('click', (e) => e.preventDefault()); // Prevent the default action
        anchor.style.pointerEvents = 'none'; // Disable click
        anchor.style.color = '#C0BEC6'; // Change appearance to indicate disabled
        if (anchor.querySelector('i') != null) {
            anchor.querySelector('i').style.color = "#C0BEC6"
        }

    });
}

function enable_tools_bar() {
    const div = document.querySelector('#tools-bar');

    if(div.classList.contains('end')){
        // enable all buttons inside the div
        div.querySelectorAll('button').forEach(button => {
            if(button.id == "downloadButton"){
                button.disabled = false;
            }
        });
    }
    else{
        // enable all buttons inside the div
        div.querySelectorAll('button').forEach(button => {
            button.disabled = false;
        });
    }

    

}

function enable_load_gedcom(){
    
    document.querySelector("#uploadGedcomBtn").disabled = false;
}


function test_max_generation() {
    if (chart == undefined) {
        return false;
    }

    nodes = chart.selectAll(".node").data()
    if (nodes == undefined || nodes == []) {
        return false;
    }

    const maxDepth = Math.max(...nodes.map(item => item.depth));

    if(maxDepth+1 > treeConfiguration.max_generation){
        document.querySelector('#max-generations-alert').style.display = "block";
        document.querySelector('#max-generations-alert div.alert').innerHTML = "You have reached the max generations available (" + treeConfiguration.max_generation + ")";
    }
    else{
        if(document.querySelector('#max-generations-alert')){
            document.querySelector('#max-generations-alert').style.display = "none";
        }
        
    }

    return maxDepth
}


function update_count(indis,generation){

    let stats = {
        'generation' : generation,
        'indis' : indis
    }
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    let fantree_id = get_fantree_id()
    $.ajax({
        url: "/fantree/updatecount/"+fantree_id,
        type: 'POST',
        data: {
            'stats': stats
        },
        dataType: 'json',
        success: function (data) {


        },
        error: function (xhr, status, error) {
            show_toast('danger', 'error', error)
            return null;
        }
    });
}
