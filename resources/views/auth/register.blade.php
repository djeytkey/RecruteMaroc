<x-guest-layout>
    @php($isCandidat = ($type ?? 'candidat') === 'candidat')
    @php($hasSocial = $isCandidat && (config('services.google.client_id') || config('services.facebook.client_id')))
    @if($hasSocial)
    <div class="mb-4 space-y-2">
        @if(config('services.google.client_id'))
        <a href="{{ route('auth.google') }}" class="inline-flex w-full justify-center rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 shadow-sm hover:bg-slate-50">
            <svg class="mr-2 h-5 w-5" viewBox="0 0 24 24"><path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/><path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/><path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/><path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/></svg>
            S'inscrire avec Google
        </a>
        @endif
        @if(config('services.facebook.client_id'))
        <a href="{{ route('auth.facebook') }}" class="inline-flex w-full justify-center rounded-lg border border-slate-300 bg-[#1877F2] px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-[#166FE5]">
            <svg class="mr-2 h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
            S'inscrire avec Facebook
        </a>
        @endif
    </div>
    <p class="mb-4 text-center text-sm text-slate-500">— ou avec votre email —</p>
    @endif

    <form method="POST" action="{{ route('register') }}">
        @csrf
        <input type="hidden" name="type" value="{{ $type ?? 'candidat' }}" />

        @if(($type ?? 'candidat') !== 'candidat')
        <div>
            <x-input-label for="name" value="Nom complet" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>
        @endif

        <div class="{{ ($type ?? 'candidat') === 'candidat' ? '' : 'mt-4' }}">
            <x-input-label for="email" value="Adresse e-mail" />
            @if(($type ?? 'candidat') === 'candidat')
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            @else
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            @endif
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        @if(($type ?? 'candidat') === 'recruteur')
        <div class="mt-4 p-3 bg-slate-50 rounded-lg">
            <p class="text-sm font-medium text-slate-700 mb-2">Informations entreprise</p>
            <div class="space-y-3">
                <div>
                    <x-input-label for="company_name" value="Nom de l'entreprise" />
                    <x-text-input id="company_name" class="block mt-1 w-full" type="text" name="company_name" :value="old('company_name')" required />
                    <x-input-error :messages="$errors->get('company_name')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="company_email" value="Email entreprise" />
                    <x-text-input id="company_email" class="block mt-1 w-full" type="email" name="company_email" :value="old('company_email')" required />
                    <x-input-error :messages="$errors->get('company_email')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="company_phone" value="Téléphone (optionnel)" />
                    <x-text-input id="company_phone" class="block mt-1 w-full" type="text" name="company_phone" :value="old('company_phone')" />
                </div>
            </div>
        </div>
        @endif

        <div class="mt-4">
            <x-input-label for="password" value="Mot de passe" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password_confirmation" value="Confirmer le mot de passe" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4 gap-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('login') }}">Déjà inscrit ?</a>
            <a class="text-sm text-emerald-600 hover:underline" href="{{ route('register') }}?type={{ ($type ?? 'candidat') === 'recruteur' ? 'candidat' : 'recruteur' }}">
                S'inscrire en tant que {{ ($type ?? 'candidat') === 'recruteur' ? 'candidat' : 'recruteur' }}
            </a>
            <x-primary-button class="ms-4">S'inscrire</x-primary-button>
        </div>
    </form>
</x-guest-layout>
