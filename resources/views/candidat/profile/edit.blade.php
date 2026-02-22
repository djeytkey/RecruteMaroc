@extends('layouts.public')

@section('title', 'Mon profil')

@section('content')
<div class="max-w-3xl">
    <h1 class="text-2xl font-bold text-slate-800 mb-6">Mon profil candidat</h1>
    @if($profile) <p class="text-slate-600 mb-4">Complétude : <strong>{{ $profile->completeness_percentage }}%</strong></p> @endif

    <form action="{{ route('candidat.profile.update') }}" method="POST" class="space-y-6 bg-white rounded-xl border border-slate-200 p-6">
        @csrf
        @method('PUT')
        <div class="grid sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Prénom *</label>
                <input type="text" name="first_name" value="{{ old('first_name', $profile?->first_name) }}" required class="w-full rounded-lg border-slate-300">
                @error('first_name')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Nom *</label>
                <input type="text" name="last_name" value="{{ old('last_name', $profile?->last_name) }}" required class="w-full rounded-lg border-slate-300">
                @error('last_name')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Téléphone</label>
            <input type="text" name="phone" value="{{ old('phone', $profile?->phone) }}" class="w-full rounded-lg border-slate-300">
        </div>
        <div class="grid sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Pays *</label>
                <input type="text" name="country" value="{{ old('country', $profile?->country ?? 'Maroc') }}" required class="w-full rounded-lg border-slate-300">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Ville</label>
                <input type="text" name="city" value="{{ old('city', $profile?->city) }}" class="w-full rounded-lg border-slate-300">
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Mobilité géographique</label>
            <select name="mobility" class="w-full rounded-lg border-slate-300">
                <option value="">—</option>
                @foreach(config('recruitment.mobility') as $k => $v)
                    <option value="{{ $k }}" @selected(old('mobility', $profile?->mobility) === $k)>{{ $v }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Disponibilité</label>
            <select name="availability" class="w-full rounded-lg border-slate-300">
                <option value="">—</option>
                @foreach(config('recruitment.availability') as $k => $v)
                    <option value="{{ $k }}" @selected(old('availability', $profile?->availability) === $k)>{{ $v }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Expérience</label>
            <select name="experience_range" class="w-full rounded-lg border-slate-300">
                <option value="">—</option>
                @foreach(config('recruitment.experience_range') as $k => $v)
                    <option value="{{ $k }}" @selected(old('experience_range', $profile?->experience_range) === $k)>{{ $v }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Dernier poste occupé</label>
            <input type="text" name="last_position" value="{{ old('last_position', $profile?->last_position) }}" class="w-full rounded-lg border-slate-300">
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Type de poste recherché</label>
            <select name="job_type_sought" class="w-full rounded-lg border-slate-300">
                <option value="">—</option>
                @foreach(config('recruitment.job_types') as $k => $v)
                    <option value="{{ $k }}" @selected(old('job_type_sought', $profile?->job_type_sought) === $k)>{{ $v }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Secteur d'activité</label>
            <select name="sector_id" class="w-full rounded-lg border-slate-300">
                <option value="">—</option>
                @foreach($sectors as $s)
                    <option value="{{ $s->id }}" @selected(old('sector_id', $profile?->sector_id) == $s->id)>{{ $s->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Niveau d'études</label>
            <select name="education_level" class="w-full rounded-lg border-slate-300">
                <option value="">—</option>
                @foreach(config('recruitment.education_levels') as $k => $v)
                    <option value="{{ $k }}" @selected(old('education_level', $profile?->education_level) === $k)>{{ $v }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-2 rounded-lg font-medium">Enregistrer le profil</button>
        </div>
    </form>

    <div class="mt-8 bg-white rounded-xl border border-slate-200 p-6">
        <h2 class="text-lg font-semibold text-slate-800 mb-2">CV (PDF obligatoire)</h2>
        @if($profile?->cv_path)
            <p class="text-slate-600 text-sm mb-2">Fichier actuel : <a href="{{ asset('storage/'.$profile->cv_path) }}" target="_blank" class="text-emerald-600 hover:underline">Télécharger</a></p>
        @endif
        <form action="{{ route('candidat.profile.cv.upload') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="file" name="cv" accept=".pdf" required class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-emerald-50 file:text-emerald-700">
            @error('cv')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            <button type="submit" class="mt-2 bg-slate-100 hover:bg-slate-200 px-4 py-2 rounded-lg font-medium text-slate-700">Téléverser le CV</button>
        </form>
    </div>
</div>
@endsection
