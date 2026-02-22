@extends('layouts.public')

@section('title', 'Offres d\'emploi')

@section('content')
<div class="flex flex-col lg:flex-row gap-8">
    <aside class="lg:w-64 shrink-0">
        <div class="bg-white rounded-xl border border-slate-200 p-4 sticky top-24">
            <h3 class="font-semibold text-slate-800 mb-3">Filtres</h3>
            <form action="{{ route('job-offers.index') }}" method="GET" class="space-y-3">
                @if(request('q'))<input type="hidden" name="q" value="{{ request('q') }}">@endif
                @if(request('location'))<input type="hidden" name="location" value="{{ request('location') }}">@endif
                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-1">Secteur</label>
                    <select name="sector" class="w-full rounded-lg border-slate-300 text-sm">
                        <option value="">Tous</option>
                        @foreach($sectors as $sector)
                            <option value="{{ $sector->id }}" @selected(request('sector') == $sector->id)>{{ $sector->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-1">Type de contrat</label>
                    <select name="contract_type" class="w-full rounded-lg border-slate-300 text-sm">
                        <option value="">Tous</option>
                        @foreach(config('recruitment.job_types') as $value => $label)
                            <option value="{{ $value }}" @selected(request('contract_type') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white py-2 rounded-lg text-sm font-medium">Appliquer</button>
            </form>
        </div>
    </aside>
    <div class="flex-1 min-w-0">
        <h1 class="text-2xl font-bold text-slate-800 mb-4">Offres d'emploi</h1>
        @if($offers->isEmpty())
            <div class="bg-white rounded-xl border border-slate-200 p-8 text-center text-slate-500">
                Aucune offre ne correspond à votre recherche. Modifiez les critères ou <a href="{{ route('job-offers.index') }}" class="text-emerald-600 hover:underline">voir toutes les offres</a>.
            </div>
        @else
            <div class="space-y-4">
                @foreach($offers as $offer)
                    <a href="{{ route('job-offers.show', $offer) }}" class="block bg-white rounded-xl border border-slate-200 p-4 hover:border-emerald-300 hover:shadow-md transition">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                            <div class="min-w-0">
                                <h2 class="font-semibold text-slate-800">{{ $offer->title }}</h2>
                                <p class="text-sm text-slate-500">{{ $offer->company->name }}</p>
                                <p class="text-xs text-slate-400 mt-1">{{ $offer->location ?? 'Maroc' }} · {{ $offer->contract_type }}@if($offer->sector) · {{ $offer->sector->name }}@endif</p>
                            </div>
                            @if($offer->recruitmentPack)
                                <span class="shrink-0 text-xs font-medium px-2 py-1 rounded" style="background-color: {{ $offer->recruitmentPack->badge_color }}20; color: {{ $offer->recruitmentPack->badge_color }};">Prime {{ number_format($offer->recruitmentPack->candidate_reward_mad, 0, '', ' ') }} MAD</span>
                            @endif
                        </div>
                        @if($offer->main_criteria)
                            <p class="text-sm text-slate-600 mt-2 line-clamp-2">{{ Str::limit($offer->main_criteria, 120) }}</p>
                        @endif
                    </a>
                @endforeach
            </div>
            <div class="mt-6">
                {{ $offers->withQueryString()->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
