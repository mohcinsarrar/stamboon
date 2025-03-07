document.getElementById('formUpdatePerson').querySelector('.firstname').addEventListener('input', function (event) { 
    
    var maxLength = 20;
    var firstname = document.getElementById('formUpdatePerson').querySelector('.firstname').value
    
    if(firstname.length > maxLength){
        document.getElementById('formUpdatePerson').querySelector('#firstname_feedback').innerHTML="your name is too long, try to short your name "
        this.value = this.value.slice(0, maxLength);
    }
    else{
        document.getElementById('formUpdatePerson').querySelector('#firstname_feedback').innerHTML=""
    }
});

document.getElementById('formUpdatePerson').querySelector('.lastname').addEventListener('input', function (event) { 
    
    var maxLength = 20;
    var lastname = document.getElementById('formUpdatePerson').querySelector('.lastname').value
    
    if(lastname.length > maxLength){
        document.getElementById('formUpdatePerson').querySelector('#lastname_feedback').innerHTML="your name is too long, try to short your name "
        this.value = this.value.slice(0, maxLength);
    }
    else{
        document.getElementById('formUpdatePerson').querySelector('#lastname_feedback').innerHTML=""
    }
});


function edit_person() {
    document.getElementById('formUpdatePerson').querySelector('#lastname_feedback').innerHTML=""
    document.getElementById('formUpdatePerson').querySelector('#firstname_feedback').innerHTML=""
    // clear update form
    var personInfo = selectedPerson
    clearForm("formUpdatePerson")
    formUpdatePerson = document.getElementById('formUpdatePerson');
    formUpdatePerson.querySelector('.death-container').classList.remove("d-none");

    // fill person id
    formUpdatePerson.querySelector('.person_id').value = personInfo.personId

    // split name to first and last name

    formUpdatePerson.querySelector('.firstname').value = personInfo.firstName
    formUpdatePerson.querySelector('.lastname').value = personInfo.lastName

    // check living or deceased radioButton
    if (personInfo.status == 'Deceased') {
        formUpdatePerson.querySelector('.deceased').checked = true;
    }
    else {
        formUpdatePerson.querySelector('.living').checked = true;
    }

    // fill birth if exist
    if (personInfo.birth != null) {
        //
        var birthDate = parseDateGlobal(personInfo.birth,target_format=date_format, target_date_style = 'number', target_separator = "-",  date_style = 'string', separator = ' ')
        formUpdatePerson.querySelector('.birth_date').value = birthDate
    }

    // change date msg
    formUpdatePerson.querySelector('#date-msg span').innerHTML = "Date format : "+date_format+" or YYYY"
    // toggle death container view
    formUpdatePerson.querySelector('.deceased').addEventListener('change', (event) => {
        if (event.target.checked) {
            formUpdatePerson.querySelector('.death-container').classList.remove("d-none");
        }
        else {
            formUpdatePerson.querySelector('.death_date').value = "";
            formUpdatePerson.querySelector('.death-container').classList.add("d-none");
        }
    });

    formUpdatePerson.querySelector('.living').addEventListener('change', (event) => {
        if (event.target.checked) {
            formUpdatePerson.querySelector('.death_date').value = "";
            formUpdatePerson.querySelector('.death-container').classList.add("d-none");
        }
        else {
            formUpdatePerson.querySelector('.death-container').classList.remove("d-none");
        }
    });

    // show death date input if Deceased
    if (personInfo.status == 'Deceased') {
        // fill death if exist
        if (personInfo.death != null) {

            var deathDate = parseDateGlobal(personInfo.death,target_format=date_format, target_date_style = 'number', target_separator = "-",  date_style = 'string', separator = ' ');
            formUpdatePerson.querySelector('.death_date').value = deathDate
        }
    }
    // hide death date id living
    else {

        formUpdatePerson.querySelector('.death-container').classList.add("d-none");
    }

    var myOffcanvas = document.getElementById('offcanvasUpdatePerson')
    var bsOffcanvas = new bootstrap.Offcanvas(myOffcanvas)
    bsOffcanvas.show()



}



// validate and send update form
document.getElementById('formUpdatePerson').addEventListener('submit', function (event) {
    event.preventDefault();
    const form = event.target;
    let isValid = true;
    let msg = "<ul>"

    // validate first name
    if (form.querySelector('.firstname').value == "") {
        isValid = false
        msg += "<li>First and middle name is required</li>"
    }

    // validate last name
    if (form.querySelector('.lastname').value == "") {
        isValid = false
        msg += "<li>Last name is required</li>"
    }

    // validate status

    if (form.querySelector('.living').check == false && form.querySelector('.deceased').value == false) {
        isValid = false
        msg += "<li>Status must be checked</li>"
    }

    // validate birth date
    var regexFullDate = /^\d{4}-\d{2}-\d{2}$/;
    const regexYearOnly = /^\d{4}$/;

    if(date_format == 'YYYY-MM-DD'){
        var regexFullDate = /^\d{4}-\d{2}-\d{2}$/;
    }
    else if(date_format == 'MM-DD-YYYY'){
        var regexFullDate = /^\d{2}-\d{2}-\d{4}$/;
    }
    else if(date_format == 'DD-MM-YYYY'){
        var regexFullDate = /^\d{2}-\d{2}-\d{4}$/;
    }
    
    

    var birth_date = form.querySelector('.birth_date').value
    
    if (birth_date != '') {
        
        if (regexFullDate.test(birth_date) || regexYearOnly.test(birth_date)) {
            
            if (!isValidDateGPT(birth_date)) {
                
                isValid = false
                msg += "<li>Please enter a valid birth date</li>"
            }
        }
        else {
            isValid = false
            msg += "<li>Please enter a valid birth date</li>"
        }
    }


    // validate death date
    var death_date = form.querySelector('.death_date').value
    if (death_date != '') {
        if (regexFullDate.test(death_date) || regexYearOnly.test(death_date)) {
            if (!isValidDateGPT(death_date)) {
                isValid = false
                msg += "<li>Please enter a valid death date</li>"
            }
        }
        else {
            isValid = false
            msg += "<li>Please enter a valid death date</li>"
        }
    }


    // test if death date is after birth date
    if (death_date != '' && birth_date != '') {
        var death_full_date = ''
        if (regexFullDate.test(death_date)) {
            death_full_date = death_date
        }
        if (regexYearOnly.test(death_date)) {
            death_full_date = death_date + '-01-01'
        }

        var birth_full_date = ''
        if (regexFullDate.test(birth_date)) {
            birth_full_date = birth_date
        }
        if (regexYearOnly.test(birth_date)) {
            birth_full_date = birth_date + '-01-01'
        }

        if (compareDates(birth_full_date, death_full_date) <= 0) {
            isValid = false
            msg += "<li>Death date must be later than Birth date</li>"
        }

    }

    msg += "</ul>"

    if (isValid === false) {
        event.preventDefault(); // Prevent form submission
        show_toast('danger', 'error', msg)
        return;
    }
    
    const formData = $(this).serialize();
    event.preventDefault();
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        url: "/pedigree/update",
        type: 'POST',
        data: formData,
        encode: true,
        dataType: 'json',
        success: function(data) {
            if (data.error == false) {
                // Handle success - you can display a success message or process the data
                
                // hide update person offcanvas
                var offCanvasElement = document.getElementById('offcanvasUpdatePerson');
                var offCanvas = bootstrap.Offcanvas.getInstance(offCanvasElement);
                offCanvas.hide();
                // hide show person info modal
                var modal = document.getElementById('nodeModal');
                modal.style.display = 'none';
                draw_tree();
                show_toast('success', 'success', data.msg)
            } else {
                // Handle the error - display an error message or take appropriate action
                show_toast('danger', 'error', data.msg)
            }
        },
        error: function(xhr, status, error) {
            // Handle any errors from the request
            show_toast('danger', 'error', error)
        }
    });
    

});