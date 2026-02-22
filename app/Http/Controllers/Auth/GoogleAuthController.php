<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    public function redirect(): RedirectResponse
    {
        $driver = Socialite::driver('google');
        if (config('services.google.stateless')) {
            $driver->stateless();
        }
        return $driver->redirect();
    }

    public function callback(): RedirectResponse
    {
        $driver = Socialite::driver('google');
        if (config('services.google.stateless')) {
            $driver->stateless();
        }
        $googleUser = $driver->user();

        $user = User::where('email', $googleUser->getEmail())->first();

        if ($user) {
            $user->update(['google_id' => $googleUser->getId()]);
        } else {
            $user = User::create([
                'name' => $googleUser->getName() ?? $googleUser->getEmail(),
                'email' => $googleUser->getEmail(),
                'password' => bcrypt(Str::random(32)),
                'role' => User::ROLE_CANDIDATE,
                'google_id' => $googleUser->getId(),
                'email_verified_at' => now(),
            ]);
        }

        Auth::login($user, true);

        return redirect()->intended(route('dashboard', absolute: false));
    }
}
