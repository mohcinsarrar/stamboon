<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Rules\DeathDate;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use App\Models\Tree;
use App\Models\Node;

class TreeImport implements ToCollection, WithHeadingRow, WithValidation
{
    use Importable;
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        //
        $actualHeaders = $rows->first()->keys()->toArray(); // Get the actual headers from the Excel file
        $expectedHeaders =  [ 
            "id",
            "ref",
            "firstname",
            "lastname",
            "birth",
            "death",
            "sex",
        ];
        
        sort($actualHeaders);
        sort($expectedHeaders);

        if ($actualHeaders === $expectedHeaders) {
            $user = Auth::user();
            $tree = Tree::where("user_id",$user->id)->first();

            foreach ($rows as $row) 
            {
                $node = Node::where('tree_id',$tree->id)
                    ->where('position',$row['ref'])
                    ->update([
                        'firstname' => $row['firstname'],
                        'lastname' => $row['lastname'],
                        'birth' => $row['birth'],
                        'death' => $row['death'],
                        'empty' => false

                    ]);
            }
        } 
        else {
            // Handle error, headers don't match
        }

        
    }

    public function isEmptyWhen(array $row): bool
    {

        if($row['firstname'] == null && $row['lastname'] == null && $row['birth'] == null && $row['death'] == null){
            return true;
        }
        else{
            return false;
        }
    }

    public function rules(): array
    {
        $positions = Node::select('position')->distinct('position')->orderBy('position')->pluck('position')->toArray();

        return [
            
            'id' => ['integer'],
            'ref' => [Rule::in($positions)],
            'firstname' => ['nullable','string'],
            'lastname' => ['nullable','string'],
            'birth' => ['nullable'],
            'death' => ['nullable'],
            'sex' => ['nullable',Rule::in(['F','M'])],
        ];
    }

    /**
     * @return array
     */
    public function customValidationMessages()
    {
        return [
            'birth.date_format' => 'the birth date must be a valid year',
            'birth.before' => 'the birth date must be before the death date, and before current year',
            'firstname.alpha' => 'the firstname must contains only lettres',
            'lastname.alpha' => 'the lastname  must contains only lettres',
            'ref.in' => 'dont change the ref column',
            'id.integer' => 'the id column must be a valid integer',
            'sex.in' => 'the sex column must be "M" or "F"',
        ];
    }


}
