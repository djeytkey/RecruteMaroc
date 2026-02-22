@extends('layouts.public')

@section('title', 'Postuler - ' . $jobOffer->title)

@section('content')
<div class="max-w-2xl">
    <h1 class="text-2xl font-bold text-slate-800 mb-2">Postuler : {{ $jobOffer->title }}</h1>
    <p class="text-slate-600 mb-6">{{ $jobOffer->company->name }}</p>

    <form action="{{ route('candidat.applications.store', $jobOffer) }}" method="POST" class="bg-white rounded-xl border border-slate-200 p-6 space-y-6">
        @csrf
        <p class="text-slate-600">Répondez aux critères de l'offre. Votre score de compatibilité sera calculé automatiquement.</p>
        @foreach($jobOffer->offerCriteria as $criterion)
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">{{ $criterion->label }} @if($criterion->is_blocking)<span class="text-red-500">*</span>@endif</label>
                @if($criterion->type === 'quantitative')
                    <select name="criteria[{{ $criterion->id }}]" class="w-full rounded-lg border-slate-300" @if($criterion->is_blocking) required @endif>
                        <option value="">—</option>
                        @foreach(config('recruitment.skill_levels') as $val => $label)
                            <option value="{{ $val }}" @selected(old('criteria.'.$criterion->id) == $val)>{{ $label }}</option>
                        @endforeach
                    </select>
                @else
                    <input type="text" name="criteria[{{ $criterion->id }}]" value="{{ old('criteria.'.$criterion->id) }}" class="w-full rounded-lg border-slate-300" @if($criterion->is_blocking) required @endif placeholder="Votre réponse">
                @endif
                @error('criteria.'.$criterion->id)<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
        @endforeach
        @if($jobOffer->offerCriteria->isEmpty())
            <p class="text-slate-500">Aucun critère spécifique. Votre candidature sera enregistrée.</p>
        @endif
        <div class="flex gap-3">
            <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-2 rounded-lg font-medium">Envoyer ma candidature</button>
            <a href="{{ route('job-offers.show', $jobOffer) }}" class="bg-slate-100 hover:bg-slate-200 px-6 py-2 rounded-lg font-medium text-slate-700">Annuler</a>
        </div>
    </form>
</div>
@endsection
