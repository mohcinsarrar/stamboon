<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Gedcom\Parser as GedcomParser;

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
            $fail('The extension of :attribute must be .ged');
        }

        // parse gedcom
        $gedcom = null;
        try {
            $file = $value->getRealPath();
            $parser = new GedcomParser();
            $gedcom = $parser->parse($file);
        } catch (\Exception $e) {
            $fail('The :attribute is not a valid GEDCOM file, (syntax errors)');
        }

    }
}
