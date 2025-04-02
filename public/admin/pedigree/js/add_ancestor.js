
document.getElementById('formAddAncestor').querySelector('.firstname').addEventListener('input', function (event) { 
    
    var maxLength = 20;
    var firstname = document.getElementById('formAddAncestor').querySelector('.firstname').value
    
    if(firstname.length > maxLength){
        document.getElementById('formAddAncestor').querySelector('#firstname_feedback').innerHTML="your name is too long, try to short your name "
        this.value = this.value.slice(0, maxLength);
    }
    else{
        document.getElementById('formAddAncestor').querySelector('#firstname_feedback').innerHTML=""
    }
});

document.getElementById('formAddAncestor').querySelector('.lastname').addEventListener('input', function (event) { 
    
    var maxLength = 20;
    var lastname = document.getElementById('formAddAncestor').querySelector('.lastname').value
    
    if(lastname.length > maxLength){
        document.getElementById('formAddAncestor').querySelector('#lastname_feedback').innerHTML="your name is too long, try to short your name "
        this.value = this.value.slice(0, maxLength);
    }
    else{
        document.getElementById('formAddAncestor').querySelector('#lastname_feedback').innerHTML=""
    }
});


function add_ancestor() {

    document.getElementById('formAddAncestor').querySelector('#lastname_feedback').innerHTML=""
    document.getElementById('formAddAncestor').querySelector('#firstname_feedback').innerHTML=""
    var personInfo = selectedPerson;
    console.log(personInfo)
    if(personInfo.parentId != "hidden_root" || personInfo.type != "person"){
        show_toast('warning', 'warning', "you can't add a ancestor for this person");
        return;
    }


    clearForm("formAddAncestor")
    var formAddAncestor = document.querySelector('#formAddAncestor');
    formAddAncestor.querySelector('.person_id').value = personInfo.personId
    formAddAncestor.querySelector('.living').checked = true;
    formAddAncestor.querySelector('.lastname').value = ""
    formAddAncestor.querySelector('.firstname').value = ""
    formAddAncestor.querySelector('.death-container').classList.add("d-none");

    // change date msg
    formAddAncestor.querySelector('#date-msg span').innerHTML = "Date format : "+date_format+" or YYYY"

    formAddAncestor.querySelector('.deceased').addEventListener('change', (event) => {
        if (event.target.checked) {
            formAddAncestor.querySelector('.death-container').classList.remove("d-none");
        }
        else {
            formAddAncestor.querySelector('.death_date_add_ancestor').value = "";
            formAddAncestor.querySelector('.death-container').classList.add("d-none");
        }
    });

    formAddAncestor.querySelector('.living').addEventListener('change', (event) => {
        if (event.target.checked) {
            formAddAncestor.querySelector('.death_date_add_ancestor').value = "";
            formAddAncestor.querySelector('.death-container').classList.add("d-none");
        }
        else {
            formAddAncestor.querySelector('.death-container').classList.remove("d-none");
        }
    });

    var myOffcanvas = document.getElementById('offcanvasAddAncestor')
    var bsOffcanvas = new bootstrap.Offcanvas(myOffcanvas)
    myOffcanvas.querySelector(".offcanvas-subtitle").innerHTML = "For : " + personInfo.name;
    bsOffcanvas.show()
}


// validate and send add ancestor form
document.getElementById('formAddAncestor').addEventListener('submit', function (event) {
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

    var birth_date = form.querySelector('.birth_date_add_ancestor').value
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
    var death_date = form.querySelector('.death_date_add_ancestor').value
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