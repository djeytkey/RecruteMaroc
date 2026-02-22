<x-guest-layout>
    <form method="POST" action="{{ route('activation.activate') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}" />
        <div>
            <x-input-label for="password" value="Nouveau mot de passe" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>
        <div class="mt-4">
            <x-input-label for="password_confirmation" value="Confirmer le mot de passe" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
        </div>
        <div class="flex items-center justify-end mt-4">
            <x-primary-button>Activer mon compte</x-primary-button>
        </div>
    </form>
</x-guest-layout>
