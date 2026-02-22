@extends('layouts.public')

@section('title', $jobOffer->title)

@section('content')
<div class="flex flex-wrap items-center justify-between gap-4 mb-6">
    <h1 class="text-2xl font-bold text-slate-800">{{ $jobOffer->title }}</h1>
    <div class="flex flex-wrap gap-2">
        <a href="{{ route('recruteur.offres.candidatures', $jobOffer) }}" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg font-medium text-sm">{{ $jobOffer->applications->count() }} Candidatures</a>
        @if($jobOffer->status === 'draft')
            @if(config('services.stripe.secret'))
                <a href="{{ route('recruteur.offres.checkout', $jobOffer) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium text-sm">Payer et publier ({{ number_format($jobOffer->recruitmentPack->price_mad, 0, '', ' ') }} MAD)</a>
            @endif
            <form action="{{ route('recruteur.offres.publish', $jobOffer) }}" method="POST" class="inline">@csrf<button type="submit" class="bg-slate-500 hover:bg-slate-600 text-white px-4 py-2 rounded-lg font-medium text-sm">Publier sans paiement (test)</button></form>
            <a href="{{ route('recruteur.offres.edit', $jobOffer) }}" class="bg-slate-100 hover:bg-slate-200 px-4 py-2 rounded-lg font-medium text-sm text-slate-700">Modifier</a>
        @endif
    </div>
</div>

<div class="bg-white rounded-xl border border-slate-200 p-6 mb-6">
    <p class="text-slate-600"><strong>Entreprise :</strong> {{ $jobOffer->company->name }}</p>
    <p class="text-slate-600"><strong>Statut :</strong> {{ $jobOffer->status }} · <strong>Type :</strong> {{ $jobOffer->contract_type }}</p>
    <div class="mt-4 prose prose-slate max-w-none"><p class="whitespace-pre-line text-slate-600">{{ $jobOffer->description }}</p></div>
</div>

<div class="bg-white rounded-xl border border-slate-200 p-6">
    <h2 class="text-lg font-semibold text-slate-800 mb-4">Critères de sélection</h2>
    @if($jobOffer->offerCriteria->isEmpty())
        <p class="text-slate-500">Aucun critère configuré.</p>
    @else
        <ul class="space-y-2">
            @foreach($jobOffer->offerCriteria as $c)
                <li>{{ $c->label }} ({{ $c->type }}, poids {{ $c->weight_percentage }}%)</li>
            @endforeach
        </ul>
    @endif
</div>
@endsection
