<?php

namespace App\Services;
use ReflectionClass;
use Illuminate\Support\Facades\Storage;
use Gedcom\Record\Indi\Even as GedcomEven;


class GedcomService
{

    private array $months = ["JAN", "FEB", "MAR", "APR", "MAY", "JUN", "JUL", "AUG", "SEP", "OCT", "NOV", "DEC"];

     

    private function dateStr($date)
    {

        $records = explode('-', $date);
        if(sizeof($records) == 1){
            $year = $records[0];
            return "$year";
        }
        $day = $records[2];
        $month = $records[1];
        $year = $records[0];
        $monthName = $this->months[$month-1];

        return "$day $monthName $year";
    }

    private function get_properties($object){
        $reflect = new ReflectionClass($object);
        $properties = $reflect->getProperties();

        return $properties;
    }

    private function get_properties_as_array($object){
        // Use ReflectionClass to inspect the object

        $properties = $this->get_properties($object);
        $data = [];
        foreach ($properties as $property) {

            list($name, $value) = $this->get_property($property,$object);
            if(!is_object($value) && !is_array($value)){
                $key = strtoupper(ltrim($name, '_')); // Remove underscore and convert to uppercase
                $data[$key] = $value;
            }

        }

        return $data;
    }

    private function get_property($property,$object){

        $property->setAccessible(true); // Make protected/private properties accessible
        $name = $property->getName();
        $value = $property->getValue($object);

        return [$name, $value];
    }

    private function get_property_by_name($object,$propertyName){
        $properties = $this->get_properties($object);
        foreach ($properties as $property) {
            [$name, $value] = $this->get_property($property,$object);
            if($name == $propertyName){
                return $value;
            }
        }

    }

    private function convert_head($object, $level , &$fileHandle, $parentName = '') {

        // Get all properties of the object (including private and protected)
        $properties = $this->get_properties($object);
    
        foreach ($properties as $property) {
            // Make the property accessible if it's private or protected
            $property->setAccessible(true);
            [$name, $value] = $this->get_property($property,$object);
            $key = strtoupper(ltrim($name, '_')); // Remove underscore and convert to uppercase
            
            // check if the object name equal to its parent name
            if($parentName == $key){
                $parentName = '';
                continue;
            }
            // If the value is an object, check its properties
            if (is_object($value)) {
                // get proprieties
                $properties = $this->get_properties_as_array($value);
                
                // Check if there's a field in the parent with the same name as the object key
                if (array_key_exists($key,$properties)) {
                    // Write the value from the parent field if it's not an object or array
                    $parentName = $key;
                    fwrite($fileHandle, "$level $key {$properties[$key]}\n");
                } else {
                    // Write the line indicating the object without value
                    $parentName = '';
                    fwrite($fileHandle, "$level $key\n");
                }
                // Recursively iterate through the object
                
                $this->convert_head($value, $level + 1, $fileHandle, $parentName);
            } 
            elseif (!is_null($value) && !is_array($value)) {
                // Store the value for potential duplication check
                $parentValues[$key] = $value; 
                fwrite($fileHandle, "$level $key $value\n");
            }
        }
    }
    
    private function convert_indis($indis, &$fileHandle){
        foreach ($indis as $key => $indi) {
            // write first level indi
            fwrite($fileHandle, "0 @$key@ INDI\n");

            // write NAME
            $name = $indi->getName();
            if($name != null && $name != []){
                $name = $name[0]->getName();
            }
            else{
                $name = null;
            }
            if($name != null){
                fwrite($fileHandle, "1 NAME $name\n");
            }
            
            // write SEX
            $sex = $indi->getSex();
            if($sex != null){
                fwrite($fileHandle, "1 SEX $sex\n");
            }
            

            // write BIRT
            $birt = $indi->getEven('BIRT');
            if($birt != null && $birt != []){
                $birt = $birt[0];
                // get birt date
                $birtDate = $birt->getDate();
                if($birtDate != null){
                    $dateStr = $this->dateStr($birtDate);
                }
                else{
                    $dateStr = '';
                }
                // get birt place
                $birtPlac = $birt->getPlac();
                if($birtPlac != null){
                    $birtPlac = $birtPlac->getPlac();
                }
                else{
                    $birtPlac = '';
                }
                
            }
            else{
                $birt == null;
            }

            if($birt != null){
                fwrite($fileHandle, "1 BIRT\n");
                if($dateStr != null){
                    fwrite($fileHandle, "2 DATE $dateStr\n");
                }
                if($birtPlac != null){
                    fwrite($fileHandle, "2 PLAC $birtPlac\n");
                }
            }
            
            // write DEAT
            $death = $indi->getEven('DEAT');
            
            
            if($death != null && $death != []){
                $death = $death[0];
                
                // get death date
                $deathDate = $death->getDate();
                if($deathDate != null){
                    $dateStr = $this->dateStr($deathDate);
                }
                else{
                    $dateStr = null;
                }

                // get death place
                $deathPlac = $death->getPlac();
                if($deathPlac != null){
                    $deathPlac = $deathPlac->getPlac();
                }
                else{
                    $deathPlac = null;
                }
                
            }
            else{
                $death == null;
            }

            if($death != null){
                fwrite($fileHandle, "1 DEAT\n");
                if($dateStr != null){
                    fwrite($fileHandle, "2 DATE $dateStr\n");
                }
                if($deathPlac != null){
                    fwrite($fileHandle, "2 PLAC $deathPlac\n");
                }
                
                
            }

            // write ADOP
            $adop = $indi->getEven('ADOP');
            if($adop != null && $adop != []){
                $adop = $adop[0];
                // get adop type
                $adopType = $adop->getAdop();

                // get adop famc
                $adopFamc = $adop->getFamc();
                
            }
            else{
                $adop == null;
            }

            if($adop  != null){
                fwrite($fileHandle, "1 ADOP\n");
                fwrite($fileHandle, "2 FAMC $adopFamc\n");
                fwrite($fileHandle, "3 ADOP $adopType\n");
            }


            // write FAMS
            $fams = $indi->getFams();
            if($fams != null && $fams != []){
                foreach($fams as $key => $family){
                    $fam = $family->getFams();
                    if($fam != null){
                        fwrite($fileHandle, "1 FAMS @$fam@\n");
                    }
                }
            }

            // write FAMC
            $famc = $indi->getFamc();
            if($famc != null && $famc != []){
                foreach($famc as $key => $family){
                    $fam = $family->getFamc();
                    if($fam != null){
                        fwrite($fileHandle, "1 FAMC @$fam@\n");
                    }

                    $pedi = $family->getPedi();
                    if($pedi != null){
                        fwrite($fileHandle, "2 PEDI $pedi\n");
                    }
                }
            }

            // write Note
            $notes = $indi->getNote();
            if($notes != null && $notes != []){
                foreach($notes as $key => $note){
                    $noteTitle = $note->getNote();
                    if($noteTitle != null){
                        fwrite($fileHandle, "1 NOTE $noteTitle\n");
                    }
                }
            }
        }
    }

    private function convert_families($families, &$fileHandle){
        foreach ($families as $key => $family){
            fwrite($fileHandle, "0 @$key@ FAM\n");

            // write HUSB
            $husb = $family->getHusb();
            if($husb != null){
                fwrite($fileHandle, "1 HUSB @$husb@\n");
            }
            
            // write WIFE
            $wife = $family->getWife();
            if($wife != null){
                fwrite($fileHandle, "1 WIFE @$wife@\n");
            }
            
            // write children
            $children = $family->getChil();
            
            foreach ($children as $key => $child){
                fwrite($fileHandle, "1 CHIL @$child@\n");
            }

            
        }
    }

    private function person_delete_event(&$person, $event){
        // get all event of person
        $events = $person->getAllEven();
        $new_events = array_diff_key($events, array($event => true));

        $reflectionClass = new ReflectionClass($person);
        $property = $reflectionClass->getProperty('even');
        $property->setAccessible(true); // Make the protected property accessible
        $property->setValue($person, $new_events);
    }

    public function writer($gedcom,$gedcom_file){
        // Define the file name
        $fileName = 'my_file.ged';
                
        // Create a temporary file
        $tempFilePath = tempnam(sys_get_temp_dir(), 'laravel_');

        // Open the file for writing
        $file = fopen($tempFilePath, 'w');


        // convert Head
        fwrite($file, "0 HEAD\n");
        $head = $gedcom->getHead();
        $this->convert_head($head, $level = 1, $file, $parentName = '');

        // convert indi
        $indis = $gedcom->getIndi();
        $this->convert_indis($indis, $file);

        // convert families
        $families = $gedcom->getFam();
        
        $this->convert_families($families, $file);

        // write footer
        fwrite($file, "0 TRLR\n");
        
        // Close the file
        fclose($file);

        // Move the file to storage
        Storage::put($gedcom_file, file_get_contents($tempFilePath));

        // delete the temporary file
        unlink($tempFilePath);

    
    }

    public function edit_death(&$person, $status, $death_date){

        // if status is deceased edit death event if exist, or add it if not exist
        if($status == "deceased"){

            // check if person has death event
            if($person->getEven('DEAT') == null){
                // no death event so create it
                $death_event = new GedcomEven;
                $death_event->setType('DEAT');
                
                $death_event->setDate($death_date);
                $person->addEven($death_event);
            }
            else{
                $death_event = $person->getEven('DEAT')[0];
                $death_event->setDate($death_date);
            }
           
        }
        // if status is living delete death event if exist
        else{
            if($person->getEven('DEAT') != null){
                $this->person_delete_event($person,'DEAT');
            }
        }

    }

    public function edit_birth(&$person, $birth_date){

        // check if person has birth event
        if($person->getEven('BIRT') == null){
            // no birth event so create it
            $birth_event = new GedcomEven;
            $birth_event->setType('BIRT');
            $birth_event->setDate($birth_date);
            $person->addEven($birth_event);
        }
        else{
            $birth_event = $person->getEven('BIRT')[0];
            $birth_event->setDate($birth_date);
        }
    }

    public function edit_name(&$person, $name){
        $names = $person->getName();
        if($names == null or $names == []){
            return null;
        }
        $firstName = $person->getName()[0];
        if($firstName == null or $firstName == []){
            return null;
        }
        $firstName->setName($name);

    }



}