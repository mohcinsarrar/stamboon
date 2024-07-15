<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Pedigree extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'status',
        'excel_file',
        'gedcom_file',
        'template'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
