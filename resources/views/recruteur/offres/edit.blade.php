@extends('layouts.public')

@section('title', 'Modifier l\'offre')

@section('content')
<h1 class="text-2xl font-bold text-slate-800 mb-6">Modifier l'offre</h1>

<form action="{{ route('recruteur.offres.update', $jobOffer) }}" method="POST" class="bg-white rounded-xl border border-slate-200 p-6 space-y-4 max-w-3xl">
    @csrf
    @method('PUT')
    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Intitulé du poste *</label>
        <input type="text" name="title" value="{{ old('title', $jobOffer->title) }}" required class="w-full rounded-lg border-slate-300">
    </div>
    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Localisation</label>
        <input type="text" name="location" value="{{ old('location', $jobOffer->location) }}" class="w-full rounded-lg border-slate-300">
    </div>
    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Secteur</label>
        <select name="sector_id" class="w-full rounded-lg border-slate-300">
            <option value="">—</option>
            @foreach($sectors as $s)
                <option value="{{ $s->id }}" @selected(old('sector_id', $jobOffer->sector_id) == $s->id)>{{ $s->name }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Type de contrat *</label>
        <select name="contract_type" required class="w-full rounded-lg border-slate-300">
            @foreach(config('recruitment.job_types') as $k => $v)
                <option value="{{ $k }}" @selected(old('contract_type', $jobOffer->contract_type) === $k)>{{ $v }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Description *</label>
        <textarea name="description" rows="5" required class="w-full rounded-lg border-slate-300">{{ old('description', $jobOffer->description) }}</textarea>
    </div>
    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Critères principaux</label>
        <textarea name="main_criteria" rows="2" class="w-full rounded-lg border-slate-300">{{ old('main_criteria', $jobOffer->main_criteria) }}</textarea>
    </div>
    <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-2 rounded-lg font-medium">Enregistrer</button>
</form>
@endsection
