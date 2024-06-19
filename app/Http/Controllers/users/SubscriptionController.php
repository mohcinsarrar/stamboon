<?php

namespace App\Http\Controllers\users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Mollie\Laravel\Facades\Mollie;
use App\Models\Payment;
use App\Models\Product;
use Carbon\Carbon;

class SubscriptionController extends Controller
{
  public function index()
  {
    $payment = Auth::user()->payment;
    $products = Product::orderByRaw('CONVERT(amount, SIGNED) asc')->get();
    return view('users.subscription.index',compact('payment','products'));
  }


  public function payment(Request $request, $id)
    {

        $product = Product::findOrFail($id);
        
        if(Auth::user()->hasSubcription()){
            $payment = Auth::user()->payment;
            if($payment->product_id == $product->id){
                return back()->with('error','The selected Plan already purchased');
            }
        }


        $payment = Mollie::api()->payments->create([
            "amount" => [
                "currency" => "USD",
                "value" => number_format($product->amount, 2, '.', '') // You must send the correct number of decimals, thus we enforce the use of strings
            ],
            "description" => $product->name,
            "redirectUrl" => route('users.subscription.success'),
            //"webhookUrl" => route('webhooks.mollie'),
            "metadata" => [
                "order_id" => time(),
            ],
        ]);

        session()->put('paymentId', $payment->id);
        session()->put('productId', $product->id);
    
        // redirect customer to Mollie checkout page
        return redirect($payment->getCheckoutUrl(), 303);
    }

    public function success(Request $request)
    {
        $paymentId = session()->get('paymentId');
        $productId = session()->get('productId');

        $product = Product::findOrFail($productId);

        $payment = Mollie::api()->payments->get($paymentId);
        if($payment->isPaid())
        {
            if(Auth::user()->hasSubcription()){
                $paymentObj = Auth::user()->payment;
            }
            else{
                $paymentObj = new Payment();
                
            }

            $paymentObj->payment_id = $paymentId;
            $paymentObj->user_id = Auth::user()->id;
            $paymentObj->product_id = $productId;
            $paymentObj->currency = $payment->amount->currency;
            $paymentObj->payment_status = "Completed";
            $paymentObj->payment_method = $payment->method;
            $paymentObj->created_at = Carbon::now();
            $paymentObj->save();
        

            session()->forget('paymentId');
            session()->forget('productId');

            return redirect()->route('users.subscription.index');
        } else {
            return redirect()->route('users.subscription.index');
        }
    }


}
