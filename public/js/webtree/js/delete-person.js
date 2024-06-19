'use strict';
document.getElementById('deletePerson').addEventListener('click', function handleClick(event) {

    Swal.fire({
        title: 'Are you sure?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        customClass: {
            confirmButton: 'btn btn-primary me-3',
            cancelButton: 'btn btn-label-secondary'
        },
        buttonsStyling: false
    }).then(function(result) {
        if (result.value) {
            var id = $('#formUpdatePerson #person_id').val()
            if (id != null) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    url: "/fanchart/deleteperson",
                    type: 'POST',
                    data: {
                        'id': id
                    },
                    encode: true,
                    dataType: 'json',
                    success: function(data) {
                        if (data.error == false) {
                            alert_msg('success', data.msg)
                            generation = parseInt($('#generations option:selected')
                                .val());
                            draw_chart(generation, fanChart)
                            var canvas = document.getElementById(
                                'offcanvasUpdatePerson')
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
    });

});