<?php

namespace App\Http\Controllers\users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Mollie\Laravel\Facades\Mollie;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Pedigree;
use App\Models\Tree;

use Carbon\Carbon;

class SubscriptionController extends Controller
{
  public function index()
  {
    $payment = Auth::user()->has_payment();
    $products = Product::orderByRaw('CONVERT(price, SIGNED) asc')->get();
    return view('users.subscription.index',compact('payment','products'));
  }


  public function payment(Request $request, $id)
    {

        $product = Product::findOrFail($id);
        
        $user = Auth::user();
        if($user->has_payment()){
            $current_payment = $user->has_payment();
            if($current_payment->product_id == $product->id){
                return back()->with('error','The selected Plan already purchased');
            }
            else{
                // user upgrade the subscription so set the current payment to expired
                $upgrade = true;
            }
        }

        $payment = Mollie::api()->payments->create([
            "amount" => [
                "currency" => "USD",
                "value" => (string)$product->price, // You must send the correct number of decimals, thus we enforce the use of strings
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
            $user = Auth::user();
            $upgrade = false;

            if($user->has_payment()){
                $current_payment = $user->has_payment();
                if($current_payment->product_id == $product->id){
                    return back()->with('error','The selected Plan already purchased');
                }
                else{
                    // user upgrade the subscription so set the current payment to expired
                    $upgrade = true;
                }
            }

            if($upgrade == true){
                Payment::where('id',$current_payment->id)->update(['expired' => 1]);
            }

            $paymentObj = new Payment();
            $paymentObj->payment_id = $paymentId;
            $paymentObj->user_id = $user->id;
            $paymentObj->product_id = $productId;
            $paymentObj->currency = $payment->amount->currency;
            $paymentObj->payment_status = "Completed";
            $paymentObj->payment_method = $payment->method;
            $paymentObj->created_at = Carbon::now();
            $paymentObj->save();
        

            session()->forget('paymentId');
            session()->forget('productId');

            // add notification
            $user->addNotification("subscription activated", "Your subscription to plan ".$product->name." paid with success");

            // reset pedigree features
            $pedigree = Pedigree::where('user_id',$user->id)->first();
            $pedigree->print_number = 0;
            $pedigree->save();

            // reset fanchart features
            $tree = Tree::where("user_id",$user->id)->first();
            $tree->print_number = 0;
            $tree->save();
            
            return redirect()->route('users.subscription.index');
        } else {
            return redirect()->route('users.subscription.index');
        }
    }


}
