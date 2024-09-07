<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pedigree extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'status',
        'excel_file',
        'gedcom_file',
        'template',
        'chart_status'
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
}
