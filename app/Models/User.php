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

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
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

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }


    public function hasSubcription()
    {
        if($this->payment == null){
            return False;
        }
        else{
            return True;
        }
    }

    public function tree(): HasOne
    {
        return $this->hasOne(Tree::class);
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
}
