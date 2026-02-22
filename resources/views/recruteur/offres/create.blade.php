@extends('layouts.public')

@section('title', 'Créer une offre')

@section('content')
<h1 class="text-2xl font-bold text-slate-800 mb-6">Créer une offre d'emploi</h1>

<form action="{{ route('recruteur.offres.store') }}" method="POST" class="bg-white rounded-xl border border-slate-200 p-6 space-y-4 max-w-3xl">
    @csrf
    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Formule / Pack *</label>
        <select name="recruitment_pack_id" required class="w-full rounded-lg border-slate-300">
            @foreach($packs as $pack)
                <option value="{{ $pack->id }}" @selected(old('recruitment_pack_id') == $pack->id)>{{ $pack->name }} — {{ number_format($pack->price_mad, 0, '', ' ') }} MAD (Prime candidat : {{ number_format($pack->candidate_reward_mad, 0, '', ' ') }} MAD)</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Intitulé du poste *</label>
        <input type="text" name="title" value="{{ old('title') }}" required class="w-full rounded-lg border-slate-300">
        @error('title')<p class="text-red-500 text-sm">{{ $message }}</p>@enderror
    </div>
    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Localisation</label>
        <input type="text" name="location" value="{{ old('location') }}" class="w-full rounded-lg border-slate-300" placeholder="Ville ou région">
    </div>
    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Secteur</label>
        <select name="sector_id" class="w-full rounded-lg border-slate-300">
            <option value="">—</option>
            @foreach($sectors as $s)
                <option value="{{ $s->id }}" @selected(old('sector_id') == $s->id)>{{ $s->name }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Type de contrat *</label>
        <select name="contract_type" required class="w-full rounded-lg border-slate-300">
            @foreach(config('recruitment.job_types') as $k => $v)
                <option value="{{ $k }}" @selected(old('contract_type') === $k)>{{ $v }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Description du poste *</label>
        <textarea name="description" rows="5" required class="w-full rounded-lg border-slate-300">{{ old('description') }}</textarea>
        @error('description')<p class="text-red-500 text-sm">{{ $message }}</p>@enderror
    </div>
    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Critères principaux (résumé)</label>
        <textarea name="main_criteria" rows="2" class="w-full rounded-lg border-slate-300">{{ old('main_criteria') }}</textarea>
    </div>
    <div class="border-t pt-4">
        <h3 class="font-medium text-slate-800 mb-2">Critères de sélection (pour le scoring)</h3>
        <p class="text-sm text-slate-500 mb-3">Ajoutez des questions/critères. Poids en % (total conseillé : 100).</p>
        <div id="criteria-list" class="space-y-3">
            <div class="criteria-row flex gap-2 items-start flex-wrap">
                <input type="text" name="criteria[0][label]" placeholder="Label (ex: Années d'expérience)" class="flex-1 min-w-[200px] rounded-lg border-slate-300">
                <select name="criteria[0][type]" class="rounded-lg border-slate-300 w-32">
                    <option value="quantitative">Quantitatif</option>
                    <option value="declarative">Déclaratif</option>
                </select>
                <input type="number" name="criteria[0][weight_percentage]" value="25" min="0" max="100" placeholder="Poids %" class="w-20 rounded-lg border-slate-300">
                <select name="criteria[0][expected_level]" class="rounded-lg border-slate-300 w-28">
                    <option value="">—</option>
                    <option value="25">25%</option>
                    <option value="50">50%</option>
                    <option value="75">75%</option>
                    <option value="100">100%</option>
                </select>
            </div>
        </div>
        <button type="button" id="add-criterion" class="mt-2 text-sm text-emerald-600 hover:underline">+ Ajouter un critère</button>
    </div>
    <div class="flex gap-3 pt-4">
        <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-2 rounded-lg font-medium">Créer l'offre</button>
        <a href="{{ route('recruteur.dashboard') }}" class="bg-slate-100 hover:bg-slate-200 px-6 py-2 rounded-lg font-medium text-slate-700">Annuler</a>
    </div>
</form>

<script>
document.getElementById('add-criterion').addEventListener('click', function() {
    const list = document.getElementById('criteria-list');
    const n = list.querySelectorAll('.criteria-row').length;
    const div = document.createElement('div');
    div.className = 'criteria-row flex gap-2 items-start flex-wrap';
    div.innerHTML = `
        <input type="text" name="criteria[${n}][label]" placeholder="Label" class="flex-1 min-w-[200px] rounded-lg border-slate-300">
        <select name="criteria[${n}][type]" class="rounded-lg border-slate-300 w-32">
            <option value="quantitative">Quantitatif</option>
            <option value="declarative">Déclaratif</option>
        </select>
        <input type="number" name="criteria[${n}][weight_percentage]" value="25" min="0" max="100" class="w-20 rounded-lg border-slate-300">
        <select name="criteria[${n}][expected_level]" class="rounded-lg border-slate-300 w-28">
            <option value="">—</option>
            <option value="25">25%</option>
            <option value="50">50%</option>
            <option value="75">75%</option>
            <option value="100">100%</option>
        </select>
    `;
    list.appendChild(div);
});
</script>
@endsection
