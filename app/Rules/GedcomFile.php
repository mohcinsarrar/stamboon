<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class GedcomFile implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {   
        
        // Check file extension
        if ($value->getClientOriginalExtension() !== 'ged') {
            $fail('The :attribute must be a valid GEDCOM file.');
        }

        // Read the file contents
        $contents = file_get_contents($value->getRealPath());

        // Remove BOM if present
        if (substr($contents, 0, 3) === "\xEF\xBB\xBF") {
            $contents = substr($contents, 3);
        }


        // Split the contents into lines
        $lines = explode("\n", $contents);
        
        // Check the first line for GEDCOM header
        if (trim($lines[0]) !== '0 HEAD') {
            $fail('The :attribute must be a valid GEDCOM file.');
        }

        // Validate the rest of the file structure
        foreach ($lines as $line) {
            if($line == ''){
                continue;
            }
            // GEDCOM lines should start with a level number (0, 1, 2, etc.)
            if (!is_numeric(explode(" ", $line)[0])) {
                $fail('The :attribute must be a valid GEDCOM file.');
            }
        }

    }
}
