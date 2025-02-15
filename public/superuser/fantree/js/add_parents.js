function add_parents(){
    var personInfo = selectedPerson;

    var formAddParents = document.querySelector('#formAddParents');

    formAddParents.querySelector("#father_container input").disabled == false;
    formAddParents.querySelector("#father_container input").checked == true;
    formAddParents.querySelector("#mother_container input").disabled == false;

    formAddParents.querySelector('.person_id').value = personInfo.id
    formAddParents.querySelector('.deceased').checked = true;
    formAddParents.querySelector('.death-container').classList.remove("d-none");

    formAddParents.querySelector('#date-msg span').innerHTML = "Date format : "+treeConfiguration.default_date+" or YYYY"

    formAddParents.querySelector('.deceased').addEventListener('change', (event) => {
        if (event.target.checked) {
            formAddParents.querySelector('.death-container').classList.remove("d-none");
        }
        else {
            formAddParents.querySelector('.death_date_add_parents').value = "";
            formAddParents.querySelector('.death-container').classList.add("d-none");
        }
    });

    formAddParents.querySelector('.living').addEventListener('change', (event) => {
        if (event.target.checked) {
            formAddParents.querySelector('.death_date_add_parents').value = "";
            formAddParents.querySelector('.death-container').classList.add("d-none");
        }
        else {
            formAddParents.querySelector('.death-container').classList.remove("d-none");
        }
    });
    
    
    // check if person has father or/and mother
    if(person_parent_length(personInfo) < 2 ){
        const hasFather = personInfo.parents.some(obj => obj.gender == 'M');
        const hasMother = personInfo.parents.some(obj => obj.gender == 'F');
        
        if(hasFather){
            formAddParents.querySelector("#father_container input").disabled = true;
            formAddParents.querySelector("#mother_container input").checked = true;
        }
        if(hasMother){
            formAddParents.querySelector("#mother_container input").disabled = true;
            formAddParents.querySelector("#father_container input").checked = true;

        }
    }


    var myOffcanvas = document.getElementById('offcanvasAddParents')
    var bsOffcanvas = new bootstrap.Offcanvas(myOffcanvas)
    bsOffcanvas.show()
}


// validate and send add spouse form
document.getElementById('formAddParents').addEventListener('submit', function (event) {
    var personInfo = selectedPerson;
    var date_format = treeConfiguration.default_date
    const form = event.target;
    let isValid = true;
    let msg = "<ul>"


    // validate parents
    const radioFather = document.getElementById("parent_type_1");
    const radioMother = document.getElementById("parent_type_2");

    // check if person has father or/and mother
    if(person_parent_length(personInfo) < 2 ){
        const hasFather = personInfo.parents.some(obj => obj.gender == 'M');
        const hasMother = personInfo.parents.some(obj => obj.gender == 'F');
        
        if(hasFather && radioFather.checked ){
            isValid = false
            msg += "<li>Person already has a father</li>"
        }
        if(hasMother && radioMother.checked){
            isValid = false
             msg += "<li>Person already has a mother</li>"

        }
    }

    if(radioFather.checked && radioMother.checked){
        isValid = false
        msg += "<li>One parent must be selected</li>"
    }

    if(!radioFather.checked && !radioMother.checked){
        isValid = false
        msg += "<li>One parent must be selected</li>"
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

    var birth_date = form.querySelector('.birth_date_add_parents').value
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
    var death_date = form.querySelector('.death_date_add_parents').value
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