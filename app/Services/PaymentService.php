<?php

namespace App\Services;
use ReflectionClass;
use Illuminate\Support\Facades\Storage;
use App\Models\Payment;
use Carbon\Carbon;


class PaymentService
{

    public function total_sales(){
        return Payment::sum('price');
    }

    public function last_month_sales(){
        $startOfLastMonth = Carbon::now()->subMonth()->startOfMonth();
        $endOfLastMonth = Carbon::now()->subMonth()->endOfMonth();

        $startOfThisMonth = Carbon::now()->startOfMonth();
        $endOfThisMonth = Carbon::now()->endOfMonth();

        $total_payments_last_month = Payment::whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])->sum('price');
        $total_payments_this_month = Payment::whereBetween('created_at', [$startOfThisMonth, $endOfThisMonth])->sum('price');


        if ($total_payments_last_month > 0) {
            $percentageChange = (($total_payments_this_month - $total_payments_last_month) / $total_payments_last_month) * 100;
        } else {
            $percentageChange = $total_payments_this_month > 0 ? 100 : 0;  // Handle edge case when last month had no users
        }

        if($percentageChange >= 0 ){
            return '+'.$percentageChange;
        }
        else{
            return $percentageChange;
        }

    }

}