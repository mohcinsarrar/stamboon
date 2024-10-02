<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

use App\Models\User;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Tree;
use App\Models\Pedigree;



use Carbon\Carbon;


class AdminDashboardService
{


    public function total_users(){
        return User::role('user')->count();
    }

    public function last_month_users(){
        $startOfLastMonth = Carbon::now()->subMonth()->startOfMonth();
        $endOfLastMonth = Carbon::now()->subMonth()->endOfMonth();

        $startOfThisMonth = Carbon::now()->startOfMonth();
        $endOfThisMonth = Carbon::now()->endOfMonth();

        $total_users_last_month = User::role('user')->whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])->count();
        $total_users_this_month = User::role('user')->whereBetween('created_at', [$startOfThisMonth, $endOfThisMonth])->count();


        if ($total_users_last_month > 0) {
            $percentageChange = (($total_users_this_month - $total_users_last_month) / $total_users_last_month) * 100;
        } else {
            $percentageChange = $total_users_this_month > 0 ? 100 : 0;  // Handle edge case when last month had no users
        }

        if($percentageChange >= 0 ){
            return '+'.$percentageChange;
        }
        else{
            return $percentageChange;
        }
        
    }

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

    public function total_fanchart_print(){
        return Tree::sum('print_number');
    }

    public function total_pedigree_print(){
        return Pedigree::sum('print_number');
    }


    public function get_products(){
        // Get all products with their names
        $allProducts = Product::pluck('name', 'id'); // Get an associative array [id => name]

        // Get the counts of each product_id from YourModel
        $productCounts = Payment::select('product_id', DB::raw('count(*) as total'))
            ->groupBy('product_id')
            ->pluck('total', 'product_id')
            ->toArray();

        // Initialize the result array with all product names and default counts of 0
        $result = [];

        foreach ($allProducts as $productId => $productName) {
            // Assign the count if it exists, otherwise default to 0
            $result[$productName] = $productCounts[$productId] ?? 0;
        }

        return $result;
    }

    public function last13j_sales(){

        $last13Days = DB::table('payments')
        ->select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('SUM(price) as total')
        )
        ->where('created_at', '>=', Carbon::now()->subDays(13))
        ->groupBy('date')
        ->get();

        // Fill in days with no payments
        $dates = [];        
        $totals = [];
        for ($i = 0; $i <= 12; $i++) {
            $date = Carbon::now()->subDays($i);
            $formattedDate = $date->format('d/m');
            $payment = $last13Days->firstWhere('date', $date->toDateString());
            // Add the date to the dates array
            $dates[] = $formattedDate;
            
            // Add the total (or 0 if no payment) to the totals array
            $totals[] = $payment ? $payment->total : 0;
        }

        $dates = array_reverse($dates);
        $totals = array_reverse($totals);

        return [
            'dates' => $dates,
            'totals' => $totals
        ];
    }
}