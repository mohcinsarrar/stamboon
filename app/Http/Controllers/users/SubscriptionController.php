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
use Illuminate\Support\Facades\Mail;
use App\Mail\SubscriptionEmail;
use LaravelCountries;
use Laravel\Cashier\Events\OrderInvoiceAvailable;

use Carbon\Carbon;

class SubscriptionController extends Controller
{
  public function index()
  {
    $user = Auth::user();
    $user_country = $user->country;
    

    $payment = Auth::user()->has_payment();
    $products = Product::orderByRaw('CONVERT(price, SIGNED) asc')->get();
    return view('users.subscription.index',compact('payment','products'));
    
  }


  private function getObjectById($array, $id) {
    $result = array_filter($array, function($object) use ($id) {
        return $object->id == $id;
    });

    // Return the first match (or null if not found)
    return $result ? array_values($result)[0] : null;
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
        /*
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
        */
        
        $countries = LaravelCountries::getCountries()->getData();
        $user_country = $this->getObjectById($countries, $user->country)->iso2;

        // Create Order using Mollie's Orders API
        $order = Mollie::api()->orders->create([
            "amount" => [
                "currency" => "USD",
                "value" => (string)$product->price, // Total price as string
            ],
            "orderNumber" => "ORDER-" . time(), // Generate unique order ID
            "lines" => [
                [
                    "type" => "digital",
                    "name" => $product->name,
                    "quantity" => 1,
                    "unitPrice" => [
                        "currency" => "USD",
                        "value" => (string)$product->price,
                    ],
                    "totalAmount" => [
                        "currency" => "USD",
                        "value" => (string)$product->price,
                    ],
                    "vatRate" => "0.00",
                    "vatAmount" => [
                        "currency" => "USD",
                        "value" => "0.00",
                    ],
                    "sku" => "sku-{$product->id}"
                ]
            ],
            "billingAddress" => [
                "givenName" => $user->firstname,  // First name
                "familyName" => $user->lastname,  // Last name
                "streetAndNumber" => $user->address,  // Street address
                "postalCode" => "12345",  // Postal code
                "city" => $user->city,  // City
                "country" => $user_country,  // Country code (e.g., 'US', 'NL')
                "email" => $user->email,
            ],
            "locale" => "en_US", // Set valid locale
            "redirectUrl" => route('users.subscription.success'),
            //"webhookUrl" => route('webhooks.mollie'),
            "metadata" => [
                "user_id" => $user->id,
                "product_id" => $product->id
            ],
        ]);

        session()->put('orderId', $order->id);
        session()->put('productId', $product->id);
    
        // redirect customer to Mollie checkout page
        
        
        return redirect($order->getCheckoutUrl(), 303);
    }

    public function success(Request $request)
    {
        $orderId = session()->get('orderId');
        $productId = session()->get('productId');

        $product = Product::findOrFail($productId);

        $order = Mollie::api()->orders->get($orderId);

        //$payment = Mollie::api()->payments->get($paymentId);
        //if($payment->isPaid())
        if ($order->isPaid() || $order->isAuthorized()) 
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
            $paymentObj->payment_id = $orderId;
            $paymentObj->user_id = $user->id;
            $paymentObj->product_id = $productId;
            $paymentObj->currency = $order->amount->currency;
            $paymentObj->price = $product->price;
            $paymentObj->payment_status = "Completed";
            $paymentObj->payment_method = $order->method;
            $paymentObj->created_at = Carbon::now();
            $paymentObj->save();
        

            session()->forget('orderId');
            session()->forget('productId');

            // add notification
            $user->addNotification("subscription activated", "Your subscription to plan ".$product->name." paid with success");

            // send email for user, with info regarding his subscription
            $title = "Your subscription to plan ".$product->name." paid with success";
            $user_fullname = $user->firstname. " " . $user->lastname;
            $content = "Thank you for signing up. We're excited to have you on board! <br> your subscription active until ". $paymentObj->active_until();
            Mail::to($user->email)->send(new SubscriptionEmail($title, $user_fullname, $content));

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
