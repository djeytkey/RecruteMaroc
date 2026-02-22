@extends('layouts.public')

@section('title', 'Espace recruteur')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-slate-800">Espace recruteur</h1>
    @if($company)
        <p class="text-slate-600 mt-1">{{ $company->name }}</p>
    @endif
</div>

<div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4 mb-8">
    <div class="bg-white rounded-xl border border-slate-200 p-4">
        <p class="text-sm text-slate-500">Annonces actives</p>
        <p class="text-2xl font-bold text-slate-800">{{ $offers->where('status', 'published')->count() }}</p>
    </div>
    <div class="bg-white rounded-xl border border-slate-200 p-4">
        <p class="text-sm text-slate-500">Total candidatures</p>
        <p class="text-2xl font-bold text-slate-800">{{ $totalApplications }}</p>
    </div>
</div>

<section class="bg-white rounded-xl border border-slate-200 p-6">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-lg font-semibold text-slate-800">Mes offres</h2>
        <a href="{{ route('recruteur.offres.create') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg font-medium text-sm">Publier une offre</a>
    </div>
    @if($offers->isEmpty())
        <p class="text-slate-500">Aucune offre. <a href="{{ route('recruteur.offres.create') }}" class="text-emerald-600 hover:underline">Créer une offre</a></p>
    @else
        <ul class="space-y-3">
            @foreach($offers as $offer)
                <li class="flex items-center justify-between py-3 border-b border-slate-100 last:border-0">
                    <div>
                        <a href="{{ route('recruteur.offres.show', $offer) }}" class="font-medium text-slate-800 hover:text-emerald-600">{{ $offer->title }}</a>
                        <p class="text-sm text-slate-500">{{ $offer->applications_count ?? $offer->applications()->count() }} candidature(s) · {{ $offer->status }}</p>
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ route('recruteur.offres.candidatures', $offer) }}" class="text-sm text-emerald-600 hover:underline">Candidatures</a>
                        @if($offer->status === 'draft')
                            <form action="{{ route('recruteur.offres.publish', $offer) }}" method="POST" class="inline">@csrf<button type="submit" class="text-sm text-blue-600 hover:underline">Publier</button></form>
                        @endif
                    </div>
                </li>
            @endforeach
        </ul>
    @endif
</section>
@endsection
