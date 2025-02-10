<?php

namespace App\Http\Controllers\users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Mollie\Laravel\Facades\Mollie;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Pedigree;
use App\Models\Fantree;
use Illuminate\Support\Facades\Mail;
use App\Mail\SubscriptionEmail;
use LaravelCountries;
use Laravel\Cashier\Events\OrderInvoiceAvailable;
use Illuminate\Support\Facades\Http;


use Carbon\Carbon;

class SubscriptionController extends Controller
{

  private $supported_currencies = [
    "AED", "AUD", "BGN", "BRL", "CAD", "CHF", "CZK", "DKK", "EUR", "GBP", "HKD", "HRK", "HUF", "ILS", "ISK", "JPY", "MXN", "MYR", "NOK", "NZD", "PHP", "PLN", "RON", "RUB", "SEK", "SGD", "THB", "TWD", "USD", "ZAR"
  ];

  private $default_currency = 'USD';


  public function index()
  {
    $user = Auth::user();
    $user_country = $user->country;
    

    $payment = Auth::user()->has_payment();
    $products = Product::orderByRaw('CONVERT(price, SIGNED) asc')->get();
    return view('users.subscription.index',compact('payment','products'));
    
  }

  private function get_vat($country, $amount){
    try {
        $vatApiKey = "967d4f730997f2425bd85a2824f99c41";
        $vatResponse = Http::get("http://apilayer.net/api/price", [
            'access_key' => $vatApiKey,
            'amount' => $amount,
            'country_code' => $country,
        ]);

        $response = $vatResponse->json();
        
        if ($response['success'] != true) {
            throw new \Exception("API request failed");
        } 

        return $vatResponse->json()['price_incl_vat'];

    } catch (\Exception $e) {
        return null;
    }
    
  }

  private function get_currency($currency){
    try {
        $curencyApiKey = "f9b3e81a943ad806539729cfe97b5d70";
        $curencyResponse = Http::get("http://apilayer.net/api/live", [
            'access_key' => $curencyApiKey,
            'source' => $this->default_currency,
            'currencies' => $currency,
            'format' => '1'
        ]);

        $response = $curencyResponse->json();

        if ($response['success'] != true) {
            throw new \Exception("API request failed");
        } 

        $firstValue = reset($response['quotes']);
        return $firstValue;
    } catch (\Exception $e){
        return null;
    }
    
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

        $amount = (string)$product->price;

        // get user country 
        $countries = LaravelCountries::getCountries()->getData();
        //dd($user->country);
        $user_country = $this->getObjectById($countries, 75);

        $user_country_code = $user_country->iso2;
        $user_currency = $user_country->currency;

        // get price include vat_rate
        $price_incl_vat = $this->get_vat($user_country_code, $amount);
        if($price_incl_vat == null){
            return redirect()->route('users.subscription.index')->with('error','your payment has not been completed, please try again');
        }

        // get currency exchange if user has a supported currency

        if(!in_array($user_currency, $this->supported_currencies)){
            $user_currency = $this->default_currency;
        }

        $currency_rate = 0;
        if($user_currency != $this->default_currency){
            $currency_rate = $this->get_currency($user_currency);
            if($currency_rate == null){
                return redirect()->route('users.subscription.index')->with('error','your payment has not been completed, please try again');
            }
        }
        
        // calculate the final price with vat and currency exchange
        
        $final_price = $price_incl_vat;
        if($currency_rate != 0){
            $final_price = $price_incl_vat * $currency_rate;
        }

        
        $final_price = number_format($final_price, 2);

        $payment = Mollie::api()->payments->create([
            "amount" => [
                "currency" => $user_currency,
                "value" => (string)$final_price, // You must send the correct number of decimals, thus we enforce the use of strings
            ],
            "description" => $product->name,
            "billingAddress" => [
                "givenName" => $user->firstname,  // First name
                "familyName" => $user->lastname,  // Last name
                "streetAndNumber" => $user->address,  // Street address
                "city" => $user->city,  // City
                "postalCode" => "12345",  // Postal code
                "country" => $user_country_code,  // Country code (e.g., 'US', 'NL')
                "email" => $user->email,
            ],
            "redirectUrl" => route('users.subscription.success'),
            "cancelUrl" => route('users.subscription.success'),
            //"webhookUrl" => route('webhooks.mollie'),

        ]);

        
        

        // Create Order using Mollie's Orders API
        /*
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
        */
        //session()->put('orderId', $order->id);
        session()->put('paymentId', $payment->id);
        session()->put('productId', $product->id);
    
        // redirect customer to Mollie checkout page
        
        
        //return redirect($order->getCheckoutUrl(), 303);
        return redirect($payment->getCheckoutUrl(), 303);
    }

    public function success(Request $request)
    {
        //$orderId = session()->get('orderId');
        $paymentId = session()->get('paymentId');
        $productId = session()->get('productId');

        $product = Product::findOrFail($productId);

        //$order = Mollie::api()->orders->get($orderId);

        $payment = Mollie::api()->payments->get($paymentId);
        // Simulate failed status

        if($payment->isPaid())
        //if ($order->isPaid() || $order->isAuthorized()) 
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
            //$paymentObj->payment_id = $orderId;
            $paymentObj->user_id = $user->id;
            $paymentObj->product_id = $productId;
            //$paymentObj->currency = $order->amount->currency;
            $paymentObj->currency = $payment->amount->currency;
            $paymentObj->price = $product->price;
            $paymentObj->payment_status = "Completed";
            //$paymentObj->payment_method = $order->method;
            $paymentObj->payment_method = $payment->method;
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

            // reset fantree features
            $fantree = Fantree::where("user_id",$user->id)->first();
            $fantree->print_number = 0;
            $fantree->save();
            
            return redirect()->route('users.subscription.index')->with('success','your payment has been completed');
        } else {
            return redirect()->route('users.subscription.index')->with('error','your payment has not been completed, please try again');
        }
    }


}
