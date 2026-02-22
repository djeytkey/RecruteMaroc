<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class ActivationController extends Controller
{
    public function show(Request $request, string $token): View|RedirectResponse
    {
        $user = User::where('activation_token', $token)->first();
        if (! $user) {
            return redirect()->route('login')->with('error', 'Lien d\'activation invalide ou expiré.');
        }
        if ($request->user() && (int) $request->user()->id !== (int) $user->id) {
            return redirect()->route('login')->with('error', 'Ce lien d\'activation ne correspond pas à votre compte.');
        }
        return view('auth.activate', ['token' => $token]);
    }

    public function pending(Request $request): View|RedirectResponse
    {
        if ($request->user()->is_active) {
            return redirect()->route('dashboard');
        }
        return view('auth.activation-pending');
    }

    public function activate(Request $request): RedirectResponse
    {
        $request->validate([
            'token' => 'required|string',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::where('activation_token', $request->token)->first();
        if (! $user) {
            return redirect()->route('login')->with('error', 'Lien d\'activation invalide ou expiré.');
        }

        $user->update([
            'password' => Hash::make($request->password),
            'activation_token' => null,
            'is_active' => true,
            'activated_at' => now(),
        ]);

        if ($request->user() && (int) $request->user()->id === (int) $user->id) {
            return redirect()->route('dashboard')->with('success', 'Compte activé. Bienvenue !');
        }

        return redirect()->route('login')->with('success', 'Compte activé. Vous pouvez vous connecter.');
    }
}
