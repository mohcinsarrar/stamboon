<?php

namespace App\Actions\Fortify;

use App\Models\User;
use App\Models\Setting;
use App\Models\Pedigree;
use App\Models\Fantree;
use App\Models\SettingFantree;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'firstname' => 'required|string',
            'lastname' => 'required|string',
            'address' => 'required|string',
            'country' => 'required|integer',
            'city' => 'required|string',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class),
            ],
            'password' => $this->passwordRules(),
            'terms' => 'required|accepted',
        ])->validate();

        $name = $input['firstname'].' '.$input['lastname'];

        $user = User::create([
            'active' => 1,
            'firstname' => $input['firstname'],
            'lastname' => $input['lastname'],
            'address' => $input['address'],
            'country' => $input['country'],
            'city' => $input['city'],
            'name' => $name,
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
            'verification_code' => str_pad(mt_rand(0, 9999), 4, '0', STR_PAD_LEFT)
        ]);

        $user->assignRole("user");
        $user->save();

        // create pedigree
        $pedigree = Pedigree::create([
            'name' => 'First Pedigree',
            'user_id' => $user->id
        ]);

        // create pedigree settings
        $setting = Setting::create(['pedigree_id' => $pedigree->id]);

        // create fantree
        $fantree = Fantree::create([
            'name' => 'First Fanchart',
            'user_id' => $user->id
        ]);

        // create fantree settings
        SettingFantree::create(['fantree_id' => $fantree->id]);

        
        
        
        return $user;
    }
}
