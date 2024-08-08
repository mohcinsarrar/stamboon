new Cleave('.death_date_add_spouse', {
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
new Cleave('.birth_date_add_spouse', {
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

function add_spouse() {
    var personInfo = selectedPerson;

    if(personInfo.spouseIds == undefined){
        show_toast('warning', 'warning', "you can't add a spouse for this person");
        return;
    }


    console.log(personInfo);
    clearForm("formAddSpouse")
    var formAddSpouse = document.querySelector('#formAddSpouse');
    formAddSpouse.querySelector('.person_id').value = personInfo.personId
    formAddSpouse.querySelector('.living').checked = true;
    formAddSpouse.querySelector('.death-container').classList.add("d-none");

    formAddSpouse.querySelector('.deceased').addEventListener('change', (event) => {
        if (event.target.checked) {
            formAddSpouse.querySelector('.death-container').classList.remove("d-none");
        }
        else {
            formAddSpouse.querySelector('.death-container').classList.add("d-none");
        }
    });

    formAddSpouse.querySelector('.living').addEventListener('change', (event) => {
        if (event.target.checked) {
            formAddSpouse.querySelector('.death-container').classList.add("d-none");
        }
        else {
            formAddSpouse.querySelector('.death-container').classList.remove("d-none");
        }
    });

    var myOffcanvas = document.getElementById('offcanvasAddSpouse')
    var bsOffcanvas = new bootstrap.Offcanvas(myOffcanvas)
    myOffcanvas.querySelector(".offcanvas-subtitle").innerHTML = "For : " + personInfo.name;
    bsOffcanvas.show()
}


// validate and send add spouse form
document.getElementById('formAddSpouse').addEventListener('submit', function (event) {
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
    if (form.querySelector('.living').checked == false && form.querySelector('.deceased').checked == false) {
        isValid = false
        msg += "<li>Status must be checked</li>"
    }

    // validate birth date
    const regexFullDate = /^\d{4}-\d{2}-\d{2}$/;
    const regexYearOnly = /^\d{4}$/;

    var birth_date = form.querySelector('.birth_date_add_spouse').value
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
    var death_date = form.querySelector('.death_date_add_spouse').value
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