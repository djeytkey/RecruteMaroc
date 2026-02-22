@extends('layouts.public')

@section('title', 'Accueil')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-slate-800 mb-2">Trouvez votre prochain emploi au Maroc</h1>
    <p class="text-slate-600">Des centaines d'offres publiées par les entreprises. Inscrivez-vous et postulez en un clic.</p>
</div>

<section class="mb-10">
    <h2 class="text-xl font-semibold text-slate-800 mb-4">Offres récentes</h2>
    @if($offers->isEmpty())
        <div class="bg-white rounded-xl border border-slate-200 p-8 text-center text-slate-500">
            Aucune offre publiée pour le moment. Revenez bientôt !
        </div>
    @else
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            @foreach($offers as $offer)
                <a href="{{ route('job-offers.show', $offer) }}" class="block bg-white rounded-xl border border-slate-200 p-4 hover:border-emerald-300 hover:shadow-md transition">
                    <div class="flex items-start justify-between gap-2">
                        <div class="min-w-0 flex-1">
                            <h3 class="font-semibold text-slate-800 truncate">{{ $offer->title }}</h3>
                            <p class="text-sm text-slate-500 mt-0.5">{{ $offer->company->name }}</p>
                            <p class="text-xs text-slate-400 mt-1">{{ $offer->location ?? 'Maroc' }} · {{ $offer->contract_type }}</p>
                        </div>
                        @if($offer->recruitmentPack)
                            <span class="shrink-0 text-xs font-medium px-2 py-0.5 rounded" style="background-color: {{ $offer->recruitmentPack->badge_color }}20; color: {{ $offer->recruitmentPack->badge_color }};">
                                Prime {{ number_format($offer->recruitmentPack->candidate_reward_mad, 0, '', ' ') }} MAD
                            </span>
                        @endif
                    </div>
                </a>
            @endforeach
        </div>
        <div class="mt-4 text-center">
            <a href="{{ route('job-offers.index') }}" class="text-emerald-600 hover:text-emerald-700 font-medium">Voir toutes les offres →</a>
        </div>
    @endif
</section>

<section class="bg-white rounded-xl border border-slate-200 p-6">
    <h2 class="text-xl font-semibold text-slate-800 mb-4">Vous êtes</h2>
    <div class="grid sm:grid-cols-2 gap-4">
        <a href="{{ route('register') }}?type=candidat" class="flex items-center gap-4 p-4 rounded-lg border-2 border-slate-200 hover:border-emerald-400 transition">
            <div class="w-12 h-12 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600 text-xl font-bold">C</div>
            <div>
                <h3 class="font-semibold text-slate-800">Candidat</h3>
                <p class="text-sm text-slate-500">Créez votre profil, déposez votre CV et postulez aux offres. Recevez une prime si vous êtes recruté.</p>
            </div>
        </a>
        <a href="{{ route('register') }}?type=recruteur" class="flex items-center gap-4 p-4 rounded-lg border-2 border-slate-200 hover:border-emerald-400 transition">
            <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 text-xl font-bold">R</div>
            <div>
                <h3 class="font-semibold text-slate-800">Recruteur / Entreprise</h3>
                <p class="text-sm text-slate-500">Publiez vos offres, recevez des candidatures notées et comparez les profils en un clic.</p>
            </div>
        </a>
    </div>
</section>
@endsection
