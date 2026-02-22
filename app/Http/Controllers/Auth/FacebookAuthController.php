<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class FacebookAuthController extends Controller
{
    public function redirect(): RedirectResponse
    {
        $driver = Socialite::driver('facebook');
        if (config('services.facebook.stateless')) {
            $driver->stateless();
        }
        return $driver->redirect();
    }

    public function callback(): RedirectResponse
    {
        $driver = Socialite::driver('facebook');
        if (config('services.facebook.stateless')) {
            $driver->stateless();
        }
        $facebookUser = $driver->user();

        if (! $facebookUser->getEmail()) {
            return redirect()->route('login')->with('error', 'Facebook n\'a pas fourni d\'adresse email. Autorisez l\'accès à votre email dans les paramètres Facebook.');
        }

        $user = User::where('email', $facebookUser->getEmail())->first();

        if ($user) {
            $user->update(['facebook_id' => $facebookUser->getId()]);
        } else {
            $user = User::create([
                'name' => $facebookUser->getName() ?? $facebookUser->getEmail(),
                'email' => $facebookUser->getEmail(),
                'password' => bcrypt(Str::random(32)),
                'role' => User::ROLE_CANDIDATE,
                'facebook_id' => $facebookUser->getId(),
                'email_verified_at' => now(),
            ]);
        }

        Auth::login($user, true);

        return redirect()->intended(route('dashboard', absolute: false));
    }
}
