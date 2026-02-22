@extends('layouts.public')

@section('title', 'Mes offres')

@section('content')
<h1 class="text-2xl font-bold text-slate-800 mb-6">Mes offres d'emploi</h1>
<p class="mb-4"><a href="{{ route('recruteur.offres.create') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg font-medium">Créer une offre</a></p>
<div class="space-y-4">
    @forelse($offers as $offer)
        <div class="bg-white rounded-xl border border-slate-200 p-4 flex flex-wrap items-center justify-between gap-2">
            <div>
                <a href="{{ route('recruteur.offres.show', $offer) }}" class="font-semibold text-slate-800 hover:text-emerald-600">{{ $offer->title }}</a>
                <p class="text-sm text-slate-500">{{ $offer->company->name }} · {{ $offer->applications()->count() }} candidature(s) · {{ $offer->status }}</p>
            </div>
            <a href="{{ route('recruteur.offres.candidatures', $offer) }}" class="text-emerald-600 hover:underline text-sm">Voir candidatures</a>
        </div>
    @empty
        <p class="text-slate-500">Aucune offre.</p>
    @endforelse
</div>
{{ $offers->links() }}
@endsection
