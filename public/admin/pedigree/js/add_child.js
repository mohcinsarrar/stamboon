new Cleave('.death_date_add_child', {
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
new Cleave('.birth_date_add_child', {
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

function add_child() {
    var personInfo = selectedPerson;
    var formAddChild = document.querySelector('#formAddChild');
    formAddChild.querySelector('.person_id').value = personInfo.personId
    formAddChild.querySelector('.person_type').value = personInfo.type
    formAddChild.querySelector('.living').checked = true;
    formAddChild.querySelector('.death-container').classList.add("d-none");

    formAddChild.querySelector('.deceased').addEventListener('change', (event) => {
        if (event.target.checked) {
            formAddChild.querySelector('.death-container').classList.remove("d-none");
        }
        else {
            formAddChild.querySelector('.death-container').classList.add("d-none");
        }
    });

    formAddChild.querySelector('.living').addEventListener('change', (event) => {
        if (event.target.checked) {
            formAddChild.querySelector('.death-container').classList.add("d-none");
        }
        else {
            formAddChild.querySelector('.death-container').classList.remove("d-none");
        }
    });

    var parents = "";
    if (personInfo.type == "spouse") {
        const person = familyData.filter((p) => p.personId === personInfo.personBeforeSpouseId);
        
        parents += `<div class="form-check">
                        <input class="form-check-input" type="radio"
                            id="defaultRadio${personInfo.name.replace(/ /g, "-")}" name="parents" value="${person[0].personId}-${personInfo.personId}" checked="true">
                        <label class="form-check-label" for="defaultRadio${personInfo.name.replace(/ /g, "-")}">
                            ${person[0].name} and ${personInfo.name}
                        </label>
                    </div>`;
    }
    else{
        for (const spouseId of personInfo.spouseIds) {
            var spouse = familyData.filter((p) => p.spouseId === spouseId);
            spouse = spouse[0];
            parents += `<div class="form-check">
                            <input class="form-check-input" type="radio"
                                id="defaultRadio${spouse.spouseName.replace(/ /g, "-")}" name="parents" value="${personInfo.personId}-${spouse.spouseId}">
                            <label class="form-check-label" for="defaultRadio${spouse.spouseName.replace(/ /g, "-")}">
                                ${personInfo.name} and ${spouse.spouseName}
                            </label>
                        </div>`;
          }
    }
    if(parents == ""){
        show_toast('warning', 'warning', "can't add child without parents");
        return;
    }

    formAddChild.querySelector('.parents_container').innerHTML = parents;

    var myOffcanvas = document.getElementById('offcanvasAddChild')
    var bsOffcanvas = new bootstrap.Offcanvas(myOffcanvas)
    bsOffcanvas.show()
}


// validate and send add spouse form
document.getElementById('formAddChild').addEventListener('submit', function (event) {
    const form = event.target;
    let isValid = true;
    let msg = "<ul>"

    // validate parents

    const parents = document.getElementsByName('parents');
    let isChecked = false;
    for (const parent of parents) {
      if (parent.checked) {
        isChecked = true;
        break;
      }
    }

    if (!isChecked) {
        isValid = false
        msg += "<li>Please select parents</li>"
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

    var birth_date = form.querySelector('.birth_date_add_child').value
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
    var death_date = form.querySelector('.death_date_add_child').value
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