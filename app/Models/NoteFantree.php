<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NoteFantree extends Model
{
    use HasFactory;

    protected $table = 'notesfantree';


    protected $fillable = [
        'fantree_id',
        'content',
        'xpos',
        'ypos',
    ];

    public function fantree(): BelongsTo
    {
        return $this->belongsTo(Fantree::class);
    }
}
