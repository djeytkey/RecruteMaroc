@extends('layouts.public')

@section('title', $jobOffer->title)

@section('content')
<article class="bg-white rounded-xl border border-slate-200 overflow-hidden">
    <div class="p-6 border-b border-slate-100">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">{{ $jobOffer->title }}</h1>
                <p class="text-slate-600 mt-1">{{ $jobOffer->company->name }}</p>
                <p class="text-sm text-slate-500 mt-1">{{ $jobOffer->location ?? 'Maroc' }} · {{ $jobOffer->contract_type }}@if($jobOffer->sector) · {{ $jobOffer->sector->name }}@endif</p>
            </div>
            @if($jobOffer->recruitmentPack)
                <span class="px-3 py-1.5 rounded-lg text-sm font-medium" style="background-color: {{ $jobOffer->recruitmentPack->badge_color }}20; color: {{ $jobOffer->recruitmentPack->badge_color }};">
                    Prime candidat recruté : {{ number_format($jobOffer->recruitmentPack->candidate_reward_mad, 0, '', ' ') }} MAD
                </span>
            @endif
        </div>
    </div>
    <div class="p-6 prose prose-slate max-w-none">
        <h2 class="text-lg font-semibold text-slate-800">Description du poste</h2>
        <div class="text-slate-600 whitespace-pre-line">{{ $jobOffer->description }}</div>
        @if($jobOffer->main_criteria)
            <h2 class="text-lg font-semibold text-slate-800 mt-6">Critères principaux</h2>
            <div class="text-slate-600 whitespace-pre-line">{{ $jobOffer->main_criteria }}</div>
        @endif
    </div>
    <div class="p-6 bg-slate-50 border-t border-slate-200">
        @auth
            @if(auth()->user()->isCandidate())
                @php $application = $jobOffer->applications()->where('user_id', auth()->id())->first(); @endphp
                @if($application)
                    <p class="text-slate-600">Vous avez déjà postulé à cette offre. Statut : <strong>{{ config('recruitment.application_status_labels')[$application->status] ?? $application->status }}</strong>@if($application->compatibility_score) — Score : {{ $application->compatibility_score }}% @endif</p>
                @else
                    <a href="{{ route('candidat.applications.create', $jobOffer) }}" class="inline-flex items-center px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-lg">Postuler à cette offre</a>
                @endif
            @endif
        @else
            <a href="{{ route('login') }}?redirect={{ urlencode(route('job-offers.show', $jobOffer)) }}" class="inline-flex items-center px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-lg">Se connecter pour postuler</a>
            <span class="text-slate-500 ml-2">ou</span>
            <a href="{{ route('register') }}?type=candidat" class="text-emerald-600 hover:underline ml-1">créer un compte candidat</a>
        @endauth
    </div>
</article>
@endsection
