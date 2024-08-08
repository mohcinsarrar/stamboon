new Cleave('.death_date', {
    delimiters: ['-', '-'],
    blocks: [4, 2, 2],
    numericOnly: true,
    onValueChanged: function(e) {
        let value = e.target.value;
        if (value.length === 4) {
            e.target.setRawValue(value); // remove delimiters if only the year is entered
        }
    }
});
new Cleave('.birth_date', {
    delimiters: ['-', '-'],
    blocks: [4, 2, 2],
    numericOnly: true,
    onValueChanged: function(e) {
        let value = e.target.value;
        if (value.length === 4) {
            e.target.setRawValue(value); // remove delimiters if only the year is entered
        }
    }
});

function edit_person() {
    // clear update form
    var personInfo = selectedPerson
    clearForm("formUpdatePerson")
    formUpdatePerson = document.getElementById('formUpdatePerson');
    formUpdatePerson.querySelector('.death-container').classList.remove("d-none");

    // fill person id
    formUpdatePerson.querySelector('.person_id').value = personInfo.personId

    // split name to first and last name
    names_array = personInfo.name.split(' ')
    formUpdatePerson.querySelector('.firstname').value = names_array.slice(0, -1).join(' ')
    formUpdatePerson.querySelector('.lastname').value = names_array[names_array.length - 1]

    // check living or deceased radioButton
    if (personInfo.status == 'Deceased') {
        formUpdatePerson.querySelector('.deceased').checked = true;
    }
    else {
        formUpdatePerson.querySelector('.living').checked = true;
    }

    // fill birth if exist
    if (personInfo.birth != null) {

        var birthDate = parseDateEdit(personInfo.birth);
        formUpdatePerson.querySelector('.birth_date').value = birthDate
    }

    // toggle death container view
    formUpdatePerson.querySelector('.deceased').addEventListener('change', (event) => {
        if (event.target.checked) {
            formUpdatePerson.querySelector('.death-container').classList.remove("d-none");
        }
        else {
            formUpdatePerson.querySelector('.death-container').classList.add("d-none");
        }
    });

    formUpdatePerson.querySelector('.living').addEventListener('change', (event) => {
        if (event.target.checked) {
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

            var deathDate = parseDateEdit(personInfo.death);
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
    const regexFullDate = /^\d{4}-\d{2}-\d{2}$/;
    const regexYearOnly = /^\d{4}$/;

    var birth_date = form.querySelector('.birth_date').value
    if (birth_date != '') {
        if (regexFullDate.test(birth_date) || regexYearOnly.test(birth_date)) {
            if (!isValidDate(birth_date)) {
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
            if (!isValidDate(death_date)) {
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
        console.log(compareDates(birth_full_date, death_full_date));
        if (compareDates(birth_full_date, death_full_date) <= 0) {
            isValid = false
            msg += "<li>Death date must be later than Birth date</li>"
        }

    }

    msg += "</ul>"

    if (!isValid) {
        event.preventDefault(); // Prevent form submission
        show_toast('danger', 'error', msg)
    }
});