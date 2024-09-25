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
        'fanchart',
        'pedigree',
        'duration',
        'print_number',
        'price',
        'fanchart_max_generation',
        'pedigree_max_generation',
        'max_nodes',
        'fanchart_max_output_png',
        'fanchart_max_output_pdf',
        'pedigree_max_output_png',
        'pedigree_max_output_pdf',
        'fanchart_output_png',
        'fanchart_output_pdf',
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
