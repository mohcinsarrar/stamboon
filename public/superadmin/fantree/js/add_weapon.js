
document.querySelector('#addWeapon').onclick = function () {

    var myModal = new bootstrap.Modal(document.getElementById('addWeaponModal'))
    document.querySelector('#addWeaponModal #weapon').value = '';

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    let fantree_id = get_fantree_id()
    $.ajax({
        url: "/superadmin/fantree/loadweapon/"+fantree_id,
        type: 'GET',
        encode: true,
        dataType: 'json',
        contentType: false,
        processData: false,
        success: function (data) {

            if (data.error == false) {
                if (data.weapon != null) {
                    document.querySelector('#addWeaponModal #show-weapon').classList.remove('d-none')
                    document.querySelector('#addWeaponModal #show-weapon img').src = "/storage/" + data.weapon
                }
                else {
                    document.querySelector('#addWeaponModal #show-weapon').classList.add('d-none')
                    document.querySelector('#addWeaponModal #show-weapon img').src = ""
                }
                myModal.show()
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

const deleteWeaponBtn = document.querySelector('#addWeaponModal #delete-weapon');
if (deleteWeaponBtn) {
    deleteWeaponBtn.onclick = function () {

        var myModal = bootstrap.Modal.getInstance(document.getElementById('addWeaponModal'));

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        let fantree_id = get_fantree_id()
        $.ajax({
            url: "/superadmin/fantree/deleteweapon/"+fantree_id,
            type: 'POST',
            data: {},
            encode: true,
            dataType: 'json',
            contentType: false,
            processData: false,
            success: function (data) {

                if (data.error == false) {
                    if(myModal != null){
                        myModal.hide();
                    }
                    
                    show_toast('success', 'weapon deleted', data.msg)
                    draw_tree()
                } else {
                    show_toast('danger', 'error', data.error)
                }
                if(myModal != null){
                    myModal.hide();
                }

            },
            error: function (xhr, status, error) {
                if(myModal != null){
                    myModal.hide();
                }
                if ('responseJSON' in xhr) {
                    show_toast('danger', 'error', xhr.responseJSON.message)
                } else {
                    show_toast('danger', 'error', error)
                }

                return null;
            }
        });
    }
}


document.querySelector('#addWeaponModal #import-weapon').onclick = function () {
    const file = $('#addWeaponModal #weapon').prop('files')[0];

    var myModal = bootstrap.Modal.getInstance(document.getElementById('addWeaponModal'));

        if (!file) {
        show_toast('danger', 'error', 'No file selected')
        return;
    }

    const formData = new FormData();
    formData.append('file', file);

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    let fantree_id = get_fantree_id()
    $.ajax({
        url: "/superadmin/fantree/addweapon/"+fantree_id,
        type: 'POST',
        data: formData,
        encode: true,
        dataType: 'json',
        contentType: false,
        processData: false,
        success: function (data) {

            if (data.error == false) {
                myModal.hide();
                show_toast('success', 'weapon added', data.msg)
                draw_tree()
            } else {
                show_toast('danger', 'error', data.error)
                myModal.hide();
            }
            

        },
        error: function (xhr, status, error) {
            myModal.hide();
            if ('responseJSON' in xhr) {
                show_toast('danger', 'error', xhr.responseJSON.message)
            } else {
                show_toast('danger', 'error', error)
            }

            return null;
        }
    });

};


function get_weapon() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    let fantree_id = get_fantree_id()
    $.ajax({
        url: "/superadmin/fantree/loadweapon/"+fantree_id,
        type: 'GET',
        encode: true,
        dataType: 'json',
        contentType: false,
        processData: false,
        success: function (data) {

            if (data.error == false) {
                if (data.weapon != null) {
                    draw_weapon(data.weapon,data.xpos,data.ypos)
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
}


// inti the drag behavior for weapon
const dragWeapon = d3.drag()
    .on("start", function (event, d) {
        // Bring the group to the front on drag start
        d3.select(this).raise();
    })
    .on("drag", function (event, d) {

        // Move the group
        d3.select(this)
            .transition()
            .duration(1)
            .attr("transform", `translate(${event.x - maxWidth / 2}, ${event.y - maxHeight / 2})`);


    })
    .on("end", (event) => {

        change_weapon_position(event.x - maxWidth / 2, event.y - maxHeight / 2);
    });


    // change position of the note in DB
function change_weapon_position(xpos, ypos) {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    let fantree_id = get_fantree_id();

    $.ajax({
        url: "/superadmin/fantree/editweaponposition/"+fantree_id,
        type: 'POST',
        data: {
            'xpos': xpos,
            'ypos': ypos
        },
        dataType: 'json',
        success: function (data) {
            if (data.error == false) {
            } else {
                return null;
            }

        },
        error: function (xhr, status, error) {
            return null;
        }
    });
}


function draw_weapon(weapon,xpos,ypos) {
    const svg = d3.select("#graph svg")
    const maxWidth = 250;
    const maxHeight = 250;

    let xPos;
    let yPos;
    if(xpos == null || ypos == null){
        xPos = svg.attr("width") - maxWidth;
        yPos = 0 ;
    }
    else{
        xPos = xpos;
        yPos = ypos;
    }
    


    const rectGroupWeapon = d3.select("#graph svg")
        .append("g")
        .attr("class", "weapon")
        .attr("transform", `translate(${xPos}, ${yPos})`)
        .on("mouseover", function () {
            d3.select(this)
                .select('#toolbar')
                .style('visibility', 'visible')
        })
        .on("mouseout", function () {
            d3.select(this)
                .select('#toolbar')
                .style('visibility', 'hidden')
        });

    rectGroupWeapon.append("foreignObject")
        .attr('x', 0) // Rectangle position relative to the group
        .attr('y', 0)
        .attr('width', maxWidth)
        .attr('height', maxHeight)
        .html(d => {


            const toolbar_style =
                `
                visibility:hidden;
                font-family:var(--bs-body-font-family); 
                margin-bottom:10px
                `;
            const toolbar =
                `
                <div id="toolbar" class="btn-group" role="group" aria-label="Basic example" style="${toolbar_style}">
                    <button onclick="edit_weapon_modal()" data-weapon-id="" id="editNote" type="button" class="p-2 btn btn-label-secondary waves-effect"><i class="ti ti-pencil fs-4"></i></button>
                    <button onclick="delete_weapon()" data-weapon-id="" id="deleteNote" type="button" class="p-2 btn btn-label-secondary waves-effect"><i class="ti ti-trash fs-4"></i></button>
                </div>
                `;


            const weapon_style = `
                width: ${maxWidth}px;
                object-fit: contain;
                padding:10px;

            `;
            const weapon_div = `
            <div>
                <img style="${weapon_style}"  src="/storage/${weapon}">
            </div>

            `;


            return `${toolbar}${weapon_div}`;
        });

    rectGroupWeapon.call(dragWeapon);

}

function edit_weapon_modal() {

    document.querySelector('#addWeapon').click()

}

function delete_weapon(){
    document.querySelector('#addWeaponModal #delete-weapon').click()
}
