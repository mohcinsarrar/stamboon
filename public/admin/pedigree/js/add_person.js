new Cleave('.birth_date_add_person', {
    delimiters: ['-', '-'],
    blocks: [4, 2, 2],
    numericOnly: true,
    onValueChanged: function(e) {
        let value = e.target.value;
        if (value.length === 5) {
            $('.birth_date_add_person').val(value.replace('-',''))
        }
    }
});
new Cleave('.death_date_add_person', {
    delimiters: ['-', '-'],
    blocks: [4, 2, 2],
    numericOnly: true,
    onValueChanged: function(e) {
        let value = e.target.value;
        if (value.length === 5) {
            $('.death_date_add_person').val(value.replace('-',''))
        }
    }
});


var formAddPerson = document.querySelector('#formAddPerson');
formAddPerson.querySelector('.living').checked = true;
formAddPerson.querySelector('.death-container').classList.add("d-none");

formAddPerson.querySelector('.deceased').addEventListener('change', (event) => {
    if (event.target.checked) {
        formAddPerson.querySelector('.death-container').classList.remove("d-none");
    }
    else {
        formAddPerson.querySelector('.death_date').value = "";
        formAddPerson.querySelector('.death-container').classList.add("d-none");
    }
});

formAddPerson.querySelector('.living').addEventListener('change', (event) => {
    if (event.target.checked) {
        formAddPerson.querySelector('.death_date').value = "";
        formAddPerson.querySelector('.death-container').classList.add("d-none");
    }
    else {
        formAddPerson.querySelector('.death-container').classList.remove("d-none");
    }
});



// validate and send add spouse form
document.getElementById('formAddPerson').addEventListener('submit', function (event) {
    const form = event.target;
    let isValid = true;
    let msg = "<ul>"

    // validate sex

    const sexs = document.getElementsByName('sex');
    let isCheckedSex = false;
    for (const sex of sexs) {
      if (sex.checked) {
        isCheckedSex = true;
        break;
      }
    }

    if (!isCheckedSex) {
        isValid = false
        msg += "<li>Please select sex</li>"
    }
    
    
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
    if (form.querySelector('.living').checked == false && form.querySelector('.deceased').checked == false) {
        isValid = false
        msg += "<li>Status must be checked</li>"
    }

    // validate birth date
    const regexFullDate = /^\d{4}-\d{2}-\d{2}$/;
    const regexYearOnly = /^\d{4}$/;

    var birth_date = form.querySelector('.birth_date_add_person').value
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
    var death_date = form.querySelector('.death_date_add_person').value
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