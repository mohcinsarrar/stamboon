'use strict';


// send form update
$("#formUpdatePerson").on("submit", function(event) {
    event.preventDefault();
    // remove validation classes
    $("input").removeClass('input_invalid')
    $('span[id$="_feedback"]').addClass('d-none')
    // get values
    var firstname = $('#formUpdatePerson #firstname').val();
    var lastname = $('#formUpdatePerson #lastname').val();
    var birth_date = $('#formUpdatePerson #birth_date').val();
    var death_date = $('#formUpdatePerson #death_date').val();
    var sex = $('#formUpdatePerson #sex').val();

    var error = false;
    // validate firstname
    if (firstname == "") {
        error = true
        $("#firstname_feedback").text("Please enter first name.")
        $("#firstname_feedback").removeClass('d-none')
        $('#formUpdatePerson #firstname').addClass('input_invalid')
    }
    // validate lastname
    if (lastname == "") {
        error = true
        $("#lastname_feedback").text('Please enter last name.')
        $("#lastname_feedback").removeClass('d-none')
        $('#formUpdatePerson #lastname').addClass('input_invalid')
    }
    

    if (sex == "Select Sex") {
        error = true
        $("#sex_feedback").text('Please select a sex.')
        $("#sex_feedback").removeClass('d-none')
        $('#formUpdatePerson #sex').addClass('input_invalid')
    }



    if (error == false) {
        // submit form
        var formData = {
            id: $('#formUpdatePerson #person_id').val(),
            firstname: firstname,
            lastname: lastname,
            birth: birth_date,
            death: death_date,
            sex: sex,
            generation: parseInt($('#generations option:selected').val()),
        };

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: "/fanchart/updateperson",
            type: 'POST',
            data: formData,
            encode: true,
            dataType: 'json',
            success: function(data) {
                if (data.error == false) {
                    alert_msg('success', data.msg)
                    generation = parseInt($('#generations option:selected').val());
                    draw_chart(generation, fanChart)
                }
                else{
                    alert_msg('error', data.msg)
                }
            },
            error: function(xhr, status, error) {
                alert_msg('error', error)
                return null;
            }
        });
        // close offcanvas
        var canvas = document.getElementById('offcanvasUpdatePerson')
        var bsOffcanvas = bootstrap.Offcanvas.getInstance(canvas)
        bsOffcanvas.hide()
    } else {
        // prevent submit
        return false;
    }




});