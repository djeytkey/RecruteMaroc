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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'type' => ['required', 'in:candidat,recruteur'],
        ];
        if ($request->input('type') === 'recruteur') {
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

        $user = User::create([
            'name' => $request->name,
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

        return redirect(route('dashboard', absolute: false));
    }
}
