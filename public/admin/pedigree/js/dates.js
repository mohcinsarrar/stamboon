function parseDateGlobal(dateStr,target_format, target_date_style = 'string', target_separator = " ",  date_style = 'string', separator = ' ') {

    // if date is null return empty string
    if(dateStr == null){
        return '';
    }

    // separate day, month and year
    const parts = dateStr.split(separator);

    // if date string contains 2, 0 or greather than 3 parts the date is invalid
    if (parts.length === 2 || parts.length === 0 || parts.length > 3) {
        return '';
    }

    // if date string contains just the year 
    if (parts.length === 1) {
        return "" + parts[0] + "";
    } 

    // if date string contains 3 parts parse it to target format
    const [day, month, year] = parts;

    // if source and traget date style are string
    if(date_style == 'string' && target_date_style == 'string'){
        if(target_format == 'YYYY-MM-DD'){
            console.log(dateStr)
            return year + target_separator + month + target_separator + day;
        }
        else if(target_format == 'MM-DD-YYYY'){
            return month + target_separator + day + target_separator + year;
        }
        else if(target_format == 'DD-MM-YYYY'){
            return day + target_separator + month + target_separator + year;
        }
    }

    if(date_style == 'string' && target_date_style == 'number'){

        var monthNames = ["JAN", "FEB", "MAR", "APR", "MAY", "JUN", "JUL", "AUG", "SEP", "OCT", "NOV", "DEC"];
        var monthIndex = monthNames.indexOf(month) + 1;
        monthIndex = monthIndex < 10 ? "0" + monthIndex.toString() : monthIndex.toString()

        if(target_format == 'YYYY-MM-DD'){
            return year + target_separator + monthIndex + target_separator + day;
        }
        else if(target_format == 'MM-DD-YYYY'){
            return monthIndex + target_separator + day + target_separator + year;
        }
        else if(target_format == 'DD-MM-YYYY'){
            return day + target_separator + monthIndex + target_separator + year;
        }
    }

    if(date_style == 'number' && target_date_style == 'string'){

        var monthNames = ["JAN", "FEB", "MAR", "APR", "MAY", "JUN", "JUL", "AUG", "SEP", "OCT", "NOV", "DEC"];
        var monthName = monthNames[parseInt(month, 10) - 1];

        if(target_format == 'YYYY-MM-DD'){
            return year + target_separator + monthName + target_separator + day;
        }
        else if(target_format == 'MM-DD-YYYY'){
            return monthName + target_separator + day + target_separator + year;
        }
        else if(target_format == 'DD-MM-YYYY'){
            return day + target_separator + monthName + target_separator + year;
        }
    }

    if(date_style == 'string' && target_date_style == 'string'){


        if(target_format == 'YYYY-MM-DD'){
            return year + target_separator + month + target_separator + day;
        }
        else if(target_format == 'MM-DD-YYYY'){
            return month + target_separator + day + target_separator + year;
        }
        else if(target_format == 'DD-MM-YYYY'){
            return day + target_separator + month + target_separator + year;
        }
    }
    

    return '';

}



//check if is a string date is valid

function checkDateValidation(dateString) {
    const date = new Date(dateString);
    // Check if date is valid by ensuring it is not an "Invalid Date" and it matches the input
    return !isNaN(date.getTime()) && date.toISOString().startsWith(dateString);
}

function isValidDate(dateString) {


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

    // Handle case for year only (YYYY)
    if (regexYearOnly.test(dateString)) {
        return true; // Valid year
    }

    if (regexFullDate.test(dateString)) {
        // First check if the string can be parsed as a date
        var isValid = checkDateValidation(dateString)
        return isValid

    }

    return false
}

function isValidDateGPT(dateString) {
    const formats = [
        /^\d{4}-\d{2}-\d{2}$/, // YYYY-MM-DD
        /^\d{2}-\d{2}-\d{4}$/, // MM-DD-YYYY
        /^\d{2}-\d{2}-\d{4}$/  // DD-MM-YYYY
    ];

    const regexYearOnly = /^\d{4}$/;

    // Handle case for year only (YYYY)
    if (regexYearOnly.test(dateString)) {
        return true; // Valid year
    }

    // Check if it matches any of the formats
    if (!formats.some(format => format.test(dateString))) {
        return false;
    }

    

    // Parse the date according to each format
    let dateParts;
    let year, month, day;

    if (/^\d{4}-\d{2}-\d{2}$/.test(dateString)) {
        // YYYY-MM-DD
        dateParts = dateString.split('-');
        year = parseInt(dateParts[0], 10);
        month = parseInt(dateParts[1], 10) - 1; // Month is 0-indexed
        day = parseInt(dateParts[2], 10);
    } else if (/^\d{2}-\d{2}-\d{4}$/.test(dateString)) {
        dateParts = dateString.split('-');
        year = parseInt(dateParts[2], 10);
        month = parseInt(dateParts[0], 10) - 1; // MM-DD-YYYY
        day = parseInt(dateParts[1], 10);

        // Optionally check for DD-MM-YYYY (ambiguous)
        if (month > 12) {
            year = parseInt(dateParts[2], 10);
            month = parseInt(dateParts[1], 10) - 1;
            day = parseInt(dateParts[0], 10);
        }
    }

    // Check if the parsed date is valid
    const date = new Date(year, month, day);
    return date.getFullYear() === year && date.getMonth() === month && date.getDate() === day;
}
