<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_id',
        'user_id',
        'product_id',
        'currency',
        'payment_status',
        'payment_method',
        'expired'
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s'
    ];

    /** Relations **/
    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function active_until(){
        $createdAt = $this->created_at;
        $product_duration = $this->product->duration;
            
        // Add the number of months to created_at
        $futureDate = $createdAt->addMonths($product_duration);

        return $futureDate->toDateString();
    }

    public function countdown(){
        $startDate = Carbon::parse($this->created_at); 
        $currentDate = Carbon::now();
        $passedDays = $startDate->diffInDays($currentDate);

        $product_duration = $this->product->duration;
        $endDate = Carbon::parse($startDate->addMonths($product_duration));

        $startDate = Carbon::parse($this->created_at); 
        $totalDays = $startDate->diffInDays($endDate);
        
        return [
            'passedDays' => $passedDays,
            'totalDays' => $totalDays,
            'percentage' => ($passedDays / $totalDays) * 100,
        ];
    }

    
}
