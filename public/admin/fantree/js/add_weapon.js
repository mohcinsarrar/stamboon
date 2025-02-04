
document.querySelector('#addWeapon').onclick = function () {

    var myModal = new bootstrap.Modal(document.getElementById('addWeaponModal'))
    document.querySelector('#addWeaponModal #weapon').value = '';

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        url: "/fantree/loadweapon",
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

        $.ajax({
            url: "/fantree/deleteweapon",
            type: 'POST',
            data: {},
            encode: true,
            dataType: 'json',
            contentType: false,
            processData: false,
            success: function (data) {

                if (data.error == false) {
                    myModal.hide();
                    show_toast('success', 'weapon deleted', data.msg)
                    draw_tree()
                } else {
                    show_toast('danger', 'error', data.error)
                }
                myModal.hide();

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

    $.ajax({
        url: "/fantree/addweapon",
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
            }
            myModal.hide();

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

    $.ajax({
        url: "/fantree/loadweapon",
        type: 'GET',
        encode: true,
        dataType: 'json',
        contentType: false,
        processData: false,
        success: function (data) {

            if (data.error == false) {
                if (data.weapon != null) {
                    draw_weapon(data.weapon)
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


function draw_weapon(weapon) {
    const svg = d3.select("#graph svg")
    const maxWidth = 250;
    const maxHeight = 250;

    const xPos = svg.attr("width") - maxWidth;
    const yPos = svg.attr("height") - maxHeight;


    const rectGroup = d3.select("#graph svg")
        .append("g")
        .attr("class", "weapon")
        .attr("transform", `translate(${xPos}, ${yPos})`);


    rectGroup.append("foreignObject")
        .attr('x', 0) // Rectangle position relative to the group
        .attr('y', 0)
        .attr('width', maxWidth)
        .attr('height', maxHeight)
        .html(d => {
            const weapon_style = `
                width: ${maxWidth}px;
                height: ${maxHeight}px;
                object-fit: contain;
                padding:10px;

            `;
            const weapon_div = `
            <div>
                <img style="${weapon_style}"  src="/storage/${weapon}">
            </div>

            `;
            return weapon_div;
        });

}