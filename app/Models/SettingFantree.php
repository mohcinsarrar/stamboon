<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SettingFantree extends Model
{
    use HasFactory;

    protected $table = 'settings_fantree';

    protected $fillable = [
        'fantree_id',
        'text_color',
        'band_color',
        'father_link_color',
        'mother_link_color',
        'female_color',
        'male_color',
        'bg_template',
        'default_male_image',
        'default_female_image',
        'default_filter',
        'default_date',
        'photos_type',
        'photos_direction',
        'note_type',
        'note_text_color'
    ];

    public function fantree(): BelongsTo
    {
        return $this->belongsTo(Fantree::class);
    }
}
