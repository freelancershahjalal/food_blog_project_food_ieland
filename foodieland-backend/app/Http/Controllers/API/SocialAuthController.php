<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class SocialAuthController extends Controller
{
    public function redirectToProvider($provider)
{
    return Socialite::driver($provider)->stateless()->redirect();
}

public function handleProviderCallback($provider)
{
    $socialUser = Socialite::driver($provider)->stateless()->user();

    $user = User::firstOrCreate(
        ['email' => $socialUser->getEmail()],
        ['name' => $socialUser->getName(), 'password' => bcrypt(Str::random(16))]
    );

    $token = $user->createToken('auth_token')->plainTextToken;

    return response()->json(['access_token' => $token, 'token_type' => 'Bearer']);
}

}
