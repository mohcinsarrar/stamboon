// clear forms
function clearForm(formId) {
    const form = document.getElementById(formId);
    const inputs = form.querySelectorAll('input');
    const selects = form.querySelectorAll('select');

    inputs.forEach(input => {
        switch (input.type) {
            case 'checkbox':
            case 'radio':
                input.checked = false;
                break;
            default:
                if (input.name != "_token") {
                    input.value = '';
                }

        }
    });

    selects.forEach(select => {
        select.selectedIndex = 0;
    });
}


// close custom modal when click on close button
function close_custom_modal() {
    var offcanvasElement = document.getElementById('offcanvasUpdatePerson');
    if (offcanvasElement.classList.contains('show')) {
        return;
    }
    var offcanvasElement = document.getElementById('offcanvasAddSpouse');
    if (offcanvasElement.classList.contains('show')) {
        return;
    }
    var offcanvasElement = document.getElementById('offcanvasAddChild');
    if (offcanvasElement.classList.contains('show')) {
        return;
    }
    var offcanvasElement = document.getElementById('modalEditImage');
    if (offcanvasElement.classList.contains('show')) {
        return;
    }
    const modal = document.getElementById('nodeModal');
    modal.style.display = 'none';
}


// compare tow string dates
function compareDates(birth, death) {
    var birth = new Date(birth);
    var death = new Date(death);

    return death - birth;
}

//check if is a string date is valid

function isValidDate(dateString) {

    // Handle case for year only (YYYY)
    if (/^\d{4}$/.test(dateString)) {
        return true; // Valid year
    }

    if (/^\d{4}-\d{2}-\d{2}$/.test(dateString)) {
        // First check if the string can be parsed as a date
        var date = new Date(dateString);
        if (isNaN(date.getTime())) {
            return false; // Invalid date
        }

        // Check if the parsed date matches the input string to handle cases like '2023-02-30'
        var parts = dateString.split(/[-/]/);
        var year = parseInt(parts[0], 10);
        var month = parseInt(parts[1], 10);
        var day = parseInt(parts[2], 10);

        return date.getFullYear() === year && (date.getMonth() + 1) === month && date.getDate() === day;
    }

    return false
    
}