<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Gedcom\Parser as GedcomParser;


class GedcomFantreeFile implements ValidationRule
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


        // test if gedcom contain valid indiv
        if($gedcom != null){
            $families = $gedcom->getFam();
            foreach($families as $key => $family){
                $husb = $family->getHusb();
                $wife = $family->getWife();
                $child = $family->getChil();

                if(count($child) == 0 ){
                    $fail('The :attribute is not a valid GEDCOM file (one family contains no child)');
                }

                if(count($child) > 1 ){
                    $fail('The :attribute is not a valid GEDCOM file (one family contains more than one child)');
                }

                if($husb == null and $wife == null){
                    $fail('The :attribute is not a valid GEDCOM file (one family contains no parents)');
                }

            }
        }

        


    }
}
