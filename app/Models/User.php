<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Auth;
use App\Models\Activity;
use App\Models\Notification;
use Carbon\Carbon;
use App\Models\Payment;
use Laravel\Fortify\TwoFactorAuthenticatable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'firstname',
        'lastname',
        'email',
        'password',
        'verification_code',
        'active',
        'status',
        'password_changed_at',
        'image'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'created_at' => 'datetime:Y-m-d',
    ];

    /** Relations **/

    public function payments(): HasMany
    {
        return $this->HasMany(Payment::class);
    }


    public function setting(): HasOne
    {
        return $this->hasOne(Setting::class);
    }

    public function tree(): HasOne
    {
        return $this->hasOne(Tree::class);
    }

    public function pedigree(): HasOne
    {
        return $this->hasOne(Pedigree::class);
    }

    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class);
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    public static function addActivity($title,$subtitle = null, $user = null){
        if($user == null){
            $user_id = Auth::user()->id;
        }
        else{
            $user_id = $user;
        }
        Activity::create([
            'title' => $title,
            'subtitle' => $subtitle,
            'user_id' => $user_id
        ]);
    }

    public static function addNotification($title,$subtitle = null, $user = null){
        
        if($user == null){
            $user_id = Auth::user()->id;
        }
        else{
            $user_id = $user;
        }

        Notification::create([
            'title' => $title,
            'subtitle' => $subtitle,
            'user_id' => $user_id
        ]);
    }

    public static function getNotification(){
        
        $notifications = Notification::where('user_id', Auth::user()->id)->get();

        return $notifications;
    }

    public static function getNotificationUnread(){
        
        $notifications = Notification::where('user_id', Auth::user()->id)->where('read_at',null)->get();

        return $notifications;
    }

    public function has_payment(){
        // get all payments
        $payments = $this->payments;

        // change payment expired field
        foreach($payments as $payment){
            $createdAt = $payment->created_at;
            $product_duration = $payment->product->duration;
            
            // Add the number of months to created_at
            $futureDate = $createdAt->addMonths($product_duration);

            if (!$futureDate->greaterThan(Carbon::today())) {
                Payment::where('id',$payment->id)->update(['expired' => 1]);
            }
        }

        // test if a not expired payment exist for the current user
        $payments = $this->payments;
        foreach($payments as $payment){

            if($payment->expired == 0){
                return $payment;
            }
        }

        return false;
    }

    public function product_type($type){

        if($this->has_payment() != false){
            $payment = $this->has_payment();
            $product = $payment->product;
            if($type == 'fanchart'){
                if($product->fanchart == true){
                    return true;
                }
                else{
                    return false;
                }
            }

            if($type == 'pedigree'){
                if($product->pedigree == true){
                    return true;
                }
                else{
                    return false;
                }
            }
        }

        return false;
    }
}
