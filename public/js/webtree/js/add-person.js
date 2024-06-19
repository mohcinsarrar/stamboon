'use strict';
function add_person(sex) {
    var id = $('#formUpdatePerson #person_id').val()
    if (id != null) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: "/fanchart/addperson",
            type: 'POST',
            data: {
                'id': id,
                'sex': sex
            },
            encode: true,
            dataType: 'json',
            success: function(data) {
                if (data.error == false) {
                    alert_msg('success', data.msg)
                    generation = parseInt($('#generations option:selected').val());
                    draw_chart(generation, fanChart)
                    var canvas = document.getElementById('offcanvasUpdatePerson')
                    var bsOffcanvas = bootstrap.Offcanvas.getInstance(canvas)
                    bsOffcanvas.hide()
                } else {
                    alert_msg('error', data.msg)
                }

            },
            error: function(xhr, status, error) {
                alert_msg('error', error)
                return null;
            }
        });
    }
}

document.getElementById('addFather').addEventListener('click', function handleClick(event) {

    add_person('M');

});

document.getElementById('addMother').addEventListener('click', function handleClick(event) {

    add_person('F');

});