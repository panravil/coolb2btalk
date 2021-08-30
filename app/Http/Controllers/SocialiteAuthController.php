<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Socialite;
use Illuminate\Support\Facades\Auth;
use Exception;

class SocialiteAuthController extends Controller
{
    public function linkedinRedirect()
    {
        return Socialite::driver('linkedin')->redirect();
    }

    /**
     * Facebook login authentication
     *
     * @return void
     */
    public function loginWithLinkedIn()
    {
        try {

            $linkedinUser = Socialite::driver('linkedin')->user();
            $user = User::where('linkedin_id', $linkedinUser->id)->first();

            if($user){
                Auth::login($user);
                return redirect('/home');
            }

            else{
                $createUser = User::create([
                    'name' => $linkedinUser->name,
                    'email' => $linkedinUser->email,
                    'linkedin_id' => $linkedinUser->id,
                    'password' => encrypt('test@123')
                ]);

                Auth::login($createUser);
                return redirect('/home');
            }

        } catch (Exception $exception) {
            dd($exception->getMessage());
        }
    }
}