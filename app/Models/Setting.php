<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'pedigree_id',
        'spouse_link_color',
        'bio_child_link_color',
        'adop_child_link_color',
        'female_color',
        'male_color',
        'node_template',
        'bg_template',
        'default_male_image',
        'default_female_image',
        'default_date',
        'note_type',
        'note_text_color',
        'default_filter',
        'photos_type'
    ];

    public function pedigree(): BelongsTo
    {
        return $this->belongsTo(Pedigree::class);
    }
}
