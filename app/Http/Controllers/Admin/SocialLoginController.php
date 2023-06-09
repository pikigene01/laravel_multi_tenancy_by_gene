<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Models\SocialLogin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Laravel\Socialite\Facades\Socialite;

class SocialLoginController extends Controller
{
    public function redirect($type)
    {
        $redirectUrl = route('social.callback', $type);
        return Socialite::driver($type)->redirectUrl($redirectUrl)->redirect();
    }
    public function callback($type)
    {
    try {
        $user = Socialite::driver($type)->user();

        $contents = file_get_contents($user->avatar);
        $name = substr($user->avatar, strrpos($user->avatar, '/') + 1);
        $picture = Storage::put("uploads/avatar/" . $user->getId() . ".png", $contents);
        $avatar = "uploads/avatar/" . $user->getId() . ".png";

        $finduser = SocialLogin::where('social_id', $user->id)->first();
        if ($finduser) {
            $existuser = User::find($finduser->user_id);

            $existuser->social_type = $type;
            $existuser->save();

            Auth::login($existuser);
            return redirect()->intended('home');
        } else {
            $checkuser =  User::where('email', $user->email)->first();

            if (!$checkuser) {

                $name = ($type  == 'github') ? $user->nickname : $user->name;
                $newUser = User::create([
                    'name' => $name,
                    'email' => $user->email,
                    'password' => Hash::make('123456dummy'),
                    'type' => 'User',
                    'lang' => 'en',
                    'created_by' => '1',
                    'plan_id' => '1',
                    'avatar' => $avatar,
                    'social_type' => $type,


                ]);
                $newUser->assignRole('User');
                $social_type = SocialLogin::create([
                    'user_id' => $newUser->id,
                    'social_type' => $type,
                    'social_id' => $user->id,

                ]);

                $newUser->social_type = $type;
                $newUser->save();

                Auth::login($newUser);
            } else {
                $social_type = SocialLogin::create([
                    'user_id' => $checkuser->id,
                    'social_type' => $type,
                    'social_id' => $user->id,
                ]);
                $checkuser->social_type = $type;
                $checkuser->save();
                Auth::login($checkuser);
            }
            return redirect()->intended('home');
        }
        } catch (Exception $e) {
          dd($e->getMessage());
        }
    }

}
