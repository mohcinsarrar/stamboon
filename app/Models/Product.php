<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'image',
        'fantree',
        'pedigree',
        'duration',
        'print_number',
        'price',
        'fantree_max_generation',
        'pedigree_max_generation',
        'max_nodes',
        'fantree_max_output_png',
        'fantree_max_output_pdf',
        'pedigree_max_output_png',
        'pedigree_max_output_pdf',
        'fantree_output_png',
        'fantree_output_pdf',
        'pedigree_output_png',
        'pedigree_output_pdf',
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d'
    ];

    
    /** Relations **/
    
    public function payments(): HasOne
    {
        return $this->HasMany(Payment::class);
    }
}
