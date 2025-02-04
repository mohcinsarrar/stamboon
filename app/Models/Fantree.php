<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use Gedcom\Parser as GedcomParser;


class Fantree extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'status',
        'excel_file',
        'gedcom_file',
        'template',
        'chart_status',
        'weapon'
    ];

    protected $casts = [
        'chart_status' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function notes(): HasMany
    {
        return $this->hasMany(Note::class);
    }

    function get_generations() {
        // Ensure the GEDCOM file exists
        if($this->gedcom_file == null){
            return 0;
        }
        if (!Storage::disk('local')->exists($this->gedcom_file)) {
            return 0;
        }
    
        $gedcom_file = $this->gedcom_file;
        $parser = new GedcomParser();
        $gedcom = $parser->parse('storage/'.$gedcom_file);
    
        // Array to store the generation level for each individual
        $childToParents = [];
    
        foreach ($gedcom->getFam() as $family) {
            $childToParents[$family->getChil()[0]] = [ 'husband_id' => $family->getHusb(), 'wife_id' => $family->getWife() ];
        }


        $root = $this->getRoot($childToParents);

        $maxGenerations = 0;

        // Start the traversal from the root child
        $this->traverseUpward($root, $childToParents, 1, $maxGenerations);

        return($maxGenerations - 1);


        
    }

    public function person_count() {
        if($this->gedcom_file == null){
            return 0;
        }
        // Ensure the GEDCOM file exists
        if (!Storage::disk('local')->exists($this->gedcom_file)) {
            return 0;
        }
    
        $gedcom_file = $this->gedcom_file;
        $parser = new GedcomParser();
        $gedcom = $parser->parse('storage/'.$gedcom_file);

        return(count($gedcom->getIndi()));
    }

    private function getRoot($childToParents) {
        // Initialize arrays for all husbands, wives, and children
        $allHusbands = [];
        $allWives = [];
        $allChildren = array_keys($childToParents);
    
        // Populate the lists of husbands and wives
        foreach ($childToParents as $rel) {
            if (isset($rel['husband_id'])) {
                $allHusbands[] = $rel['husband_id'];
            }
            if (isset($rel['wife_id'])) {
                $allWives[] = $rel['wife_id'];
            }
        }
    
        // Filter out potential roots (children who are not husbands or wives)
        $potentialRoots = array_filter($allChildren, function($child) use ($allHusbands, $allWives) {
            return !in_array($child, $allHusbands) && !in_array($child, $allWives);
        });
    
        // Return the filtered list
        return array_values($potentialRoots)[0]; // Re-index the array
    }

    private function traverseUpward($currentChild, $familyTree, $currentGeneration, &$maxGenerations) {
        $maxGenerations = max($maxGenerations, $currentGeneration);
    
        if (isset($familyTree[$currentChild])) {
            $parents = $familyTree[$currentChild];
    
            // Traverse the father's branch
            if (isset($parents['husband_id'])) {
                $this->traverseUpward($parents['husband_id'], $familyTree, $currentGeneration + 1, $maxGenerations);
            }
    
            // Traverse the mother's branch
            if (isset($parents['wife_id'])) {
                $this->traverseUpward($parents['wife_id'], $familyTree, $currentGeneration + 1, $maxGenerations);
            }
        }
    }
    
}
