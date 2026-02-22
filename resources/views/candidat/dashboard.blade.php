@extends('layouts.public')

@section('title', 'Mon espace candidat')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-slate-800">Mon espace candidat</h1>
    @if($profile)
        <p class="text-slate-600 mt-1">Profil complété à {{ $profile->completeness_percentage }}% — <a href="{{ route('candidat.profile.edit') }}" class="text-emerald-600 hover:underline">Compléter / modifier</a></p>
    @else
        <p class="text-amber-600 mt-1">Complétez votre profil et déposez votre CV pour postuler. <a href="{{ route('candidat.profile.edit') }}" class="text-emerald-600 hover:underline">Créer mon profil</a></p>
    @endif
</div>

<div class="grid gap-6 lg:grid-cols-2">
    <section class="bg-white rounded-xl border border-slate-200 p-6">
        <h2 class="text-lg font-semibold text-slate-800 mb-4">Mes candidatures récentes</h2>
        @if($applications->isEmpty())
            <p class="text-slate-500">Aucune candidature. <a href="{{ route('job-offers.index') }}" class="text-emerald-600 hover:underline">Voir les offres</a></p>
        @else
            <ul class="space-y-3">
                @foreach($applications as $app)
                    <li class="flex items-center justify-between py-2 border-b border-slate-100 last:border-0">
                        <div>
                            <a href="{{ route('job-offers.show', $app->jobOffer) }}" class="font-medium text-slate-800 hover:text-emerald-600">{{ $app->jobOffer->title }}</a>
                            <p class="text-sm text-slate-500">{{ $app->jobOffer->company->name }} — {{ config('recruitment.application_status_labels')[$app->status] ?? $app->status }}@if($app->compatibility_score) · {{ $app->compatibility_score }}% @endif</p>
                        </div>
                    </li>
                @endforeach
            </ul>
            <a href="{{ route('candidat.applications.index') }}" class="inline-block mt-3 text-emerald-600 hover:underline text-sm">Toutes mes candidatures →</a>
        @endif
    </section>
    <section class="bg-white rounded-xl border border-slate-200 p-6">
        <h2 class="text-lg font-semibold text-slate-800 mb-4">Actions rapides</h2>
        <div class="space-y-2">
            <a href="{{ route('candidat.profile.edit') }}" class="block p-3 rounded-lg border border-slate-200 hover:border-emerald-300">Modifier mon profil / CV</a>
            <a href="{{ route('job-offers.index') }}" class="block p-3 rounded-lg border border-slate-200 hover:border-emerald-300">Rechercher une offre</a>
        </div>
    </section>
</div>
@endsection
