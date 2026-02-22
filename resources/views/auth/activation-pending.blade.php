<x-guest-layout>
    <div class="rounded-lg bg-amber-50 p-4 text-amber-800">
        <p class="font-medium">Compte en attente d'activation</p>
        <p class="mt-2 text-sm">Un email vous a été envoyé avec un lien pour activer votre compte recruteur. Cliquez sur le lien dans l'email pour définir votre mot de passe et activer l'accès à votre espace.</p>
        <p class="mt-2 text-sm">Si vous ne trouvez pas l'email, vérifiez vos spams ou déconnectez-vous puis réessayez plus tard.</p>
    </div>
    <form method="POST" action="{{ route('logout') }}" class="mt-4">
        @csrf
        <button type="submit" class="w-full rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 shadow-sm hover:bg-slate-50">
            Se déconnecter
        </button>
    </form>
</x-guest-layout>
