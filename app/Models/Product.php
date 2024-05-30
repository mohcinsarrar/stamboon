<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'amount',
        'description',
        'features',
    ];

    protected $casts = [
        'features' => 'array',
        'created_at' => 'datetime:Y-m-d'
    ];

    
    /** Relations **/
    
    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }
}
