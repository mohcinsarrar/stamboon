<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;


class Node extends Model
{
    use HasFactory;


    protected $fillable = [
        'firstname',
        'lastname',
        'sex',
        'birth',
        'death',
        'image',
        'pid',
        'root',
        'tree_id',
        'generation',
        'position',
        'empty'
    ];


    public function tree(): BelongsTo
    {
        return $this->belongsTo(Tree::class);
    }

     // Relationship: Many Nodes belong to one Parent Node
    public function parent()
    {
        return $this->belongsTo(Node::class, 'pid');
    }
 
     // Relationship: One Node has many Child Nodes
    public function children()
    {
        return $this->hasMany(Node::class, 'pid')
            ->select('id','firstname','lastname','sex','birth','death','pid','image','position','empty','root','generation')
            ->orderBy('sex','desc');
            
    }

    protected function Firstname(): Attribute
    {
        
        return Attribute::make(
            get: fn ($value) => ($value == null) ? "person" : $value,
        );
    }

    protected function Lastname(): Attribute
    {
        
        return Attribute::make(
            get: fn ($value) => ($value == null) ? "person" : $value,
        );
    }

    protected function Birth(): Attribute
    {
        
        return Attribute::make(
            get: fn ($value) => ($value == null) ? "" : $value,
        );
    }

    protected function Death(): Attribute
    {
        
        return Attribute::make(
            get: fn ($value) => ($value == null) ? "" : $value,
        );
    }


    public function scopeCustomOrder($query)
    {
        return $query->orderByRaw("LENGTH(position), position");
    }

    

    public static function createFamilyTree($firstname, $lastname, $sexe, $tree_id)
    {
        // create the root node
        $rootPerson = Node::create([
            'firstname' => $firstname,
            'lastname' => $lastname,
            'sex' => $sexe,
            'birth' => null,
            'death' => null,
            "root" => true,
            "empty" => false,
            'tree_id' => $tree_id,
            'position' => '1A',
            'generation' => 1
        ]);
        $maxGeneration = 7;
        $currentGeneration = 1;
        self::generateChildren($tree_id, $rootPerson, $currentGeneration , $maxGeneration);
    }

    public static function generateChildren($tree_id, $parent, $currentGeneration, $maxGeneration)
    {

        if ($currentGeneration >= $maxGeneration) {
            return;
        }

        // Assuming each person in level 2 has two children
        $childrenCount = 2;

        for ($i = 0; $i < $childrenCount; $i++) {
            
            $sex = ($i % 2 === 0) ? 'M' : 'F'; // Alternate 'M' and 'F'
            if(($currentGeneration + 1) == 2){
                $empty = False;
            }
            else{
                $empty = True;
            }
            $position = Node::where('tree_id',$tree_id)->where('generation',$currentGeneration + 1)->count()+1;
            $position = ($currentGeneration + 1).self::convertIntToChar($position);
            $child = Node::create([
                'firstname' => null,
                'lastname' => null,
                'sex' => $sex,
                'birth' => null,
                'death' => null,
                'empty' => $empty,
                'pid' => $parent->id,
                'tree_id' => $tree_id,
                'position' => $position,
                'generation' => $currentGeneration + 1,
            ]);

            // Recursively generate children for each child
            self::generateChildren($tree_id, $child, $currentGeneration + 1, $maxGeneration);
        }
    }

    public static function convertIntToChar($number)
    {
        $number = $number - 1;
        $r = '';
        for ($i = 1; $number >= 0 && $i < 10; $i++) {
        $r = chr(0x41 + ($number % pow(26, $i) / pow(26, $i - 1))) . $r;
        $number -= pow(26, $i);
        }
        return $r;

        return $result;
    }

    public function updateChildrenEmptyField($isEmpty)
    {
        $this->empty = $isEmpty;
        $this->save();

        foreach ($this->children as $child) {
            $child->updateChildrenEmptyField($isEmpty);
        }
    }

    public static function updateChildrenRecursively(Node $node, $isEmpty)
    {
        $node->updateChildrenEmptyField($isEmpty);
    }
}
