<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\RecruiterActivationMail;
use App\Models\Company;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(Request $request): View
    {
        $type = $request->query('type', 'candidat');
        return view('auth.register', ['type' => $type === 'recruteur' ? 'recruteur' : 'candidat']);
    }

    public function store(Request $request): RedirectResponse
    {
        $rules = [
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'type' => ['required', 'in:candidat,recruteur'],
        ];
        if ($request->input('type') === 'recruteur') {
            $rules['name'] = ['required', 'string', 'max:255'];
            $rules['company_name'] = ['required', 'string', 'max:255'];
            $rules['company_email'] = ['required', 'email'];
            $rules['company_phone'] = ['nullable', 'string', 'max:30'];
        }
        $request->validate($rules);

        $role = $request->type === 'recruteur' ? User::ROLE_RECRUITER : User::ROLE_CANDIDATE;
        $companyId = null;
        $isActive = true;
        $activationToken = null;

        if ($role === User::ROLE_RECRUITER) {
            $company = Company::create([
                'name' => $request->company_name,
                'email' => $request->company_email,
                'phone' => $request->company_phone,
                'is_activated' => true,
            ]);
            $companyId = $company->id;
            $activationToken = Str::random(64);
            $isActive = config('recruitment.send_activation_email', false) ? false : true;
        }

        $name = $role === User::ROLE_CANDIDATE
            ? 'À compléter'
            : $request->name;

        $user = User::create([
            'name' => $name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $role,
            'company_id' => $companyId ?? null,
            'is_active' => $isActive ?? true,
            'activation_token' => $activationToken ?? null,
        ]);

        if ($role === User::ROLE_RECRUITER && !empty($activationToken)) {
            $activationUrl = route('activation.show', ['token' => $activationToken]);
            Mail::to($user->email)->send(new RecruiterActivationMail($user, $activationUrl));
        }

        event(new Registered($user));

        Auth::login($user);

        if ($role === User::ROLE_CANDIDATE) {
            return redirect()->route('candidat.profile.edit')
                ->with('success', 'Compte créé. Complétez votre profil pour continuer.');
        }

        return redirect(route('dashboard', absolute: false));
    }
}
