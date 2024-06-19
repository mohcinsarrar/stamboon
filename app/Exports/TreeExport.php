<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\Auth;
use App\Models\Tree;
use App\Models\Node;
use App\Models\User;

class TreeExport implements FromCollection,WithHeadings
{

    protected $user_id;

    public function __construct(int $user_id)
    {
        $this->user_id = $user_id;
    }

    public function headings(): array
    {
        return [
            'ref',
            'Firstname',
            'Lastname',
            'Birth',
            'Death',
            'Sex'
        ];
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        //
        $user = User::with('tree')->findOrFail($this->user_id);
        $tree = $user->tree;
        return Node::select('position','firstname','lastname','birth','death','sex')
                    ->where('tree_id',$tree->id)
                    ->orderByRaw("CAST(SUBSTRING(position, 1, 1) AS UNSIGNED),LENGTH(position), position")
                    ->get();
        
    }
}
