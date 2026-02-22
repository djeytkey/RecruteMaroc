@extends('layouts.public')

@section('title', 'Mes candidatures')

@section('content')
<h1 class="text-2xl font-bold text-slate-800 mb-6">Mes candidatures</h1>
<div class="space-y-4">
    @forelse($applications as $app)
        <div class="bg-white rounded-xl border border-slate-200 p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
            <div>
                <a href="{{ route('job-offers.show', $app->jobOffer) }}" class="font-semibold text-slate-800 hover:text-emerald-600">{{ $app->jobOffer->title }}</a>
                <p class="text-sm text-slate-500">{{ $app->jobOffer->company->name }} · {{ $app->jobOffer->location ?? 'Maroc' }}</p>
                <p class="text-xs text-slate-400 mt-1">Statut : {{ config('recruitment.application_status_labels')[$app->status] ?? $app->status }}@if($app->compatibility_score) — Score : {{ $app->compatibility_score }}% @endif</p>
            </div>
        </div>
    @empty
        <p class="text-slate-500">Aucune candidature. <a href="{{ route('job-offers.index') }}" class="text-emerald-600 hover:underline">Voir les offres</a></p>
    @endforelse
</div>
<div class="mt-6">{{ $applications->links() }}</div>
@endsection
