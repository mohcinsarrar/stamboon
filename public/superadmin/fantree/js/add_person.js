

document.getElementById('add-first-person').addEventListener('click', (event) => {
    add_person()
  });


function add_person(){

    var formAddPerson = document.querySelector('#formAddPerson');

    formAddPerson.querySelector('#date-msg span').innerHTML = "Date format : "+treeConfiguration.default_date+" or YYYY"
    formAddPerson.querySelector('.living').checked = true;
    formAddPerson.querySelector('.death-container').classList.add("d-none");

    formAddPerson.querySelector('.deceased').addEventListener('change', (event) => {
        if (event.target.checked) {
            formAddPerson.querySelector('.death-container').classList.remove("d-none");
        }
        else {
            formAddPerson.querySelector('.death_date_add_person').value = "";
            formAddPerson.querySelector('.death-container').classList.add("d-none");
        }
    });

    formAddPerson.querySelector('.living').addEventListener('change', (event) => {
        if (event.target.checked) {
            formAddPerson.querySelector('.death_date_add_person').value = "";
            formAddPerson.querySelector('.death-container').classList.add("d-none");
        }
        else {
            formAddPerson.querySelector('.death-container').classList.remove("d-none");
        }
    });


    var myOffcanvas = document.getElementById('offcanvasAddPerson')
    var bsOffcanvas = new bootstrap.Offcanvas(myOffcanvas)
    bsOffcanvas.show()
}




// validate and send add spouse form
document.getElementById('formAddPerson').addEventListener('submit', function (event) {

    

    const form = event.target;
    let date_format = treeConfiguration.default_date;
    let isValid = true;
    let msg = "<ul>"

    // change date msg
    
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

    var birth_date = form.querySelector('.birth_date_add_person').value
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
    var death_date = form.querySelector('.death_date_add_person').value
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