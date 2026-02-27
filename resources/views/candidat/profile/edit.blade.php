@extends('layouts.public')

@section('title', 'Mon profil')

@section('content')
<div class="max-w-3xl">
    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <h1 class="text-2xl font-bold text-slate-800">Mon profil candidat</h1>
        <button type="button" id="btn-open-carte-candidat" class="inline-flex items-center gap-2 bg-slate-100 hover:bg-slate-200 text-slate-700 px-4 py-2 rounded-lg font-medium">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
            Visualiser la carte candidat
        </button>
    </div>

    <div class="flex items-center gap-6 mb-6 p-4 bg-white rounded-xl border border-slate-200">
        <div class="relative flex-shrink-0 flex items-center gap-3">
            <div class="relative" style="width: 80px; height: 80px;">
                @php
                    $pct = $profile ? (float) $profile->completeness_percentage : 0;
                    $circlePath = 'M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831';
                    $lenR = min(25, $pct);
                    $lenJ = min(25, max(0, $pct - 25));
                    $lenO = min(25, max(0, $pct - 50));
                    $lenV = min(25, max(0, $pct - 75));
                @endphp
                <svg class="w-full h-full -rotate-90" viewBox="0 0 36 36">
                    <defs>
                        <linearGradient id="gradProfil1" gradientUnits="userSpaceOnUse" x1="18" y1="2" x2="34" y2="18"><stop offset="0%" stop-color="#ef4444"/><stop offset="100%" stop-color="#fbbf24"/></linearGradient>
                        <linearGradient id="gradProfil2" gradientUnits="userSpaceOnUse" x1="34" y1="18" x2="18" y2="34"><stop offset="0%" stop-color="#fbbf24"/><stop offset="100%" stop-color="#f97316"/></linearGradient>
                        <linearGradient id="gradProfil3" gradientUnits="userSpaceOnUse" x1="18" y1="34" x2="2" y2="18"><stop offset="0%" stop-color="#f97316"/><stop offset="100%" stop-color="#ea580c"/></linearGradient>
                        <linearGradient id="gradProfil4" gradientUnits="userSpaceOnUse" x1="2" y1="18" x2="18" y2="2"><stop offset="0%" stop-color="#ea580c"/><stop offset="100%" stop-color="#10b981"/></linearGradient>
                    </defs>
                    <path stroke="#e2e8f0" stroke-width="3" fill="none" d="{{ $circlePath }}" />
                    @if($lenR > 0)<path stroke="url(#gradProfil1)" stroke-width="3" fill="none" stroke-linecap="butt" stroke-dasharray="{{ $lenR }}, 100" stroke-dashoffset="0" d="{{ $circlePath }}" />@endif
                    @if($lenJ > 0)<path stroke="url(#gradProfil2)" stroke-width="3" fill="none" stroke-linecap="butt" stroke-dasharray="{{ $lenJ }}, 100" stroke-dashoffset="-25" d="{{ $circlePath }}" />@endif
                    @if($lenO > 0)<path stroke="url(#gradProfil3)" stroke-width="3" fill="none" stroke-linecap="butt" stroke-dasharray="{{ $lenO }}, 100" stroke-dashoffset="-50" d="{{ $circlePath }}" />@endif
                    @if($lenV > 0)<path stroke="url(#gradProfil4)" stroke-width="3" fill="none" stroke-linecap="butt" stroke-dasharray="{{ $lenV }}, 100" stroke-dashoffset="-75" d="{{ $circlePath }}" />@endif
                </svg>
                <span class="absolute inset-0 flex items-center justify-center text-sm font-bold text-slate-700">{{ round($pct) }}%</span>
            </div>
        </div>
        <div>
            <p class="font-medium text-slate-800">Taux de complétude du profil</p>
            <p class="text-sm text-slate-500">Complétez les champs pour améliorer votre visibilité.</p>
        </div>
    </div>

    <form action="{{ route('candidat.profile.update') }}" method="POST" class="space-y-6 bg-white rounded-xl border border-slate-200 p-6" id="profile-form" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="flex flex-wrap items-start gap-6">
            <div class="flex-shrink-0">
                @if($profile?->photo_path)
                    <img src="{{ asset('storage/'.$profile->photo_path) }}" width="100" height="100" alt="Ma photo" class="w-24 h-24 rounded-full object-cover border-2 border-slate-200">
                @else
                    <div class="w-24 h-24 rounded-full bg-slate-200 flex items-center justify-center text-slate-500 text-sm text-center px-1">Photo</div>
                @endif
            </div>
            <div class="flex-1 min-w-[200px]">
                <label class="block text-sm font-medium text-slate-700 mb-1">Photo du profil</label>
                <input type="file" name="photo" accept="image/jpeg,image/png,image/jpg" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-emerald-50 file:text-emerald-700">
                <p class="text-xs text-slate-500 mt-1">JPG ou PNG, max. 2 Mo.</p>
                @error('photo')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
        </div>
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

        <div class="border-t border-slate-200 pt-6">
            <h2 class="text-lg font-semibold text-slate-800 mb-3">Compétences</h2>
            <p class="text-sm text-slate-500 mb-3">Ajoutez vos compétences et indiquez votre niveau.</p>
            <div id="skills-container" class="space-y-3">
                @php $skills = old('skills', $profile?->skills ?? []); @endphp
                @if(count($skills))
                    @foreach($skills as $i => $skill)
                        <div class="skill-row flex flex-wrap gap-2 items-end">
                            <input type="text" name="skills[{{ $i }}][name]" value="{{ $skill['name'] ?? '' }}" placeholder="Ex: PHP, Excel…" class="flex-1 min-w-[120px] rounded-lg border-slate-300">
                            <select name="skills[{{ $i }}][level]" class="w-48 rounded-lg border-slate-300">
                                @foreach(config('recruitment.skill_levels') as $k => $v)
                                    <option value="{{ $k }}" @selected(($skill['level'] ?? '') == $k)>{{ $v }}</option>
                                @endforeach
                            </select>
                            <button type="button" class="remove-skill text-slate-400 hover:text-red-600 p-2" title="Supprimer">✕</button>
                        </div>
                    @endforeach
                @else
                    <div class="skill-row flex flex-wrap gap-2 items-end">
                        <input type="text" name="skills[0][name]" placeholder="Ex: PHP, Excel…" class="flex-1 min-w-[120px] rounded-lg border-slate-300">
                        <select name="skills[0][level]" class="w-48 rounded-lg border-slate-300">
                            @foreach(config('recruitment.skill_levels') as $k => $v)
                                <option value="{{ $k }}">{{ $v }}</option>
                            @endforeach
                        </select>
                        <button type="button" class="remove-skill text-slate-400 hover:text-red-600 p-2" title="Supprimer">✕</button>
                    </div>
                @endif
            </div>
            <button type="button" id="add-skill" class="mt-2 text-sm text-emerald-600 hover:text-emerald-700 font-medium">+ Ajouter une compétence</button>
        </div>

        <div class="border-t border-slate-200 pt-6">
            <h2 class="text-lg font-semibold text-slate-800 mb-3">Langues</h2>
            <p class="text-sm text-slate-500 mb-3">Indiquez les langues que vous parlez et votre niveau.</p>
            <div id="languages-container" class="space-y-3">
                @php $languages = old('languages', $profile?->languages ?? []); @endphp
                @if(count($languages))
                    @foreach($languages as $i => $lang)
                        <div class="language-row flex flex-wrap gap-2 items-end">
                            <input type="text" name="languages[{{ $i }}][language]" value="{{ $lang['language'] ?? '' }}" placeholder="Ex: Français, Anglais…" class="flex-1 min-w-[120px] rounded-lg border-slate-300">
                            <select name="languages[{{ $i }}][level]" class="w-48 rounded-lg border-slate-300">
                                @foreach(config('recruitment.language_levels') as $k => $v)
                                    <option value="{{ $k }}" @selected(($lang['level'] ?? '') === $k)>{{ $v }}</option>
                                @endforeach
                            </select>
                            <button type="button" class="remove-language text-slate-400 hover:text-red-600 p-2" title="Supprimer">✕</button>
                        </div>
                    @endforeach
                @else
                    <div class="language-row flex flex-wrap gap-2 items-end">
                        <input type="text" name="languages[0][language]" placeholder="Ex: Français, Anglais…" class="flex-1 min-w-[120px] rounded-lg border-slate-300">
                        <select name="languages[0][level]" class="w-48 rounded-lg border-slate-300">
                            @foreach(config('recruitment.language_levels') as $k => $v)
                                <option value="{{ $k }}">{{ $v }}</option>
                            @endforeach
                        </select>
                        <button type="button" class="remove-language text-slate-400 hover:text-red-600 p-2" title="Supprimer">✕</button>
                    </div>
                @endif
            </div>
            <button type="button" id="add-language" class="mt-2 text-sm text-emerald-600 hover:text-emerald-700 font-medium">+ Ajouter une langue</button>
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

{{-- Modal Carte candidat --}}
<div id="modal-carte-candidat" class="fixed inset-0 z-50 hidden" aria-hidden="true">
    <div class="fixed inset-0 bg-black/60" id="modal-carte-backdrop" style="background-color: rgba(0, 0, 0, 0.6);"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4 overflow-y-auto">
        <div class="relative bg-white rounded-2xl shadow-xl max-w-2xl w-full max-h-[90vh] overflow-hidden flex flex-col">
            <div class="flex items-center justify-between p-6 border-b border-slate-200 flex-shrink-0">
                <h2 class="text-lg font-semibold text-slate-800">Carte candidat</h2>
                <button type="button" id="btn-close-carte-candidat" class="p-2 text-slate-400 hover:text-slate-600 rounded-lg" aria-label="Fermer">✕</button>
            </div>
            <div class="p-6 overflow-y-auto flex-1">
                @if(!$profile)
                    <p class="text-slate-600">Complétez et enregistrez votre profil pour afficher la carte candidat.</p>
                @else
                    <article class="carte-candidat-body">
                        <div class="bg-slate-800 text-white p-5 rounded-t-xl flex items-center gap-4">
                            @if($profile->photo_path)
                                <img src="{{ asset('storage/'.$profile->photo_path) }}" alt="{{ $profile->full_name }}" class="flex-shrink-0 w-16 h-16 rounded-full object-cover border-2 border-slate-600">
                            @else
                                <div class="flex-shrink-0 w-16 h-16 rounded-full bg-slate-600 flex items-center justify-center text-xl font-bold text-slate-300">
                                    {{ strtoupper(mb_substr($profile->first_name ?? '', 0, 1) . mb_substr($profile->last_name ?? '', 0, 1)) }}
                                </div>
                            @endif
                            <div class="flex-1 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-1 min-w-0">
                                <div>
                                    <h3 class="text-lg font-bold">{{ $profile->full_name }}</h3>
                                    @if($profile->last_position)<p class="text-slate-300 text-sm mt-0.5">{{ $profile->last_position }}</p>@endif
                                </div>
                                @php
                                    $pctCarte = (float) $profile->completeness_percentage;
                                    $pathCarte = 'M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831';
                                    $lenRC = min(25, $pctCarte);
                                    $lenJC = min(25, max(0, $pctCarte - 25));
                                    $lenOC = min(25, max(0, $pctCarte - 50));
                                    $lenVC = min(25, max(0, $pctCarte - 75));
                                @endphp
                                <div class="flex-shrink-0 flex flex-col items-center">
                                    <div class="relative inline-flex" style="width: 70px; height: 70px;">
                                        <svg class="w-full h-full -rotate-90" viewBox="0 0 36 36">
                                            <defs>
                                                <linearGradient id="gradCarte1" gradientUnits="userSpaceOnUse" x1="18" y1="2" x2="34" y2="18"><stop offset="0%" stop-color="#ef4444"/><stop offset="100%" stop-color="#fbbf24"/></linearGradient>
                                                <linearGradient id="gradCarte2" gradientUnits="userSpaceOnUse" x1="34" y1="18" x2="18" y2="34"><stop offset="0%" stop-color="#fbbf24"/><stop offset="100%" stop-color="#f97316"/></linearGradient>
                                                <linearGradient id="gradCarte3" gradientUnits="userSpaceOnUse" x1="18" y1="34" x2="2" y2="18"><stop offset="0%" stop-color="#f97316"/><stop offset="100%" stop-color="#ea580c"/></linearGradient>
                                                <linearGradient id="gradCarte4" gradientUnits="userSpaceOnUse" x1="2" y1="18" x2="18" y2="2"><stop offset="0%" stop-color="#ea580c"/><stop offset="100%" stop-color="#10b981"/></linearGradient>
                                            </defs>
                                            <path stroke="#475569" stroke-width="3" fill="none" d="{{ $pathCarte }}" />
                                            @if($lenRC > 0)<path stroke="url(#gradCarte1)" stroke-width="3" fill="none" stroke-linecap="butt" stroke-dasharray="{{ $lenRC }}, 100" stroke-dashoffset="0" d="{{ $pathCarte }}" />@endif
                                            @if($lenJC > 0)<path stroke="url(#gradCarte2)" stroke-width="3" fill="none" stroke-linecap="butt" stroke-dasharray="{{ $lenJC }}, 100" stroke-dashoffset="-25" d="{{ $pathCarte }}" />@endif
                                            @if($lenOC > 0)<path stroke="url(#gradCarte3)" stroke-width="3" fill="none" stroke-linecap="butt" stroke-dasharray="{{ $lenOC }}, 100" stroke-dashoffset="-50" d="{{ $pathCarte }}" />@endif
                                            @if($lenVC > 0)<path stroke="url(#gradCarte4)" stroke-width="3" fill="none" stroke-linecap="butt" stroke-dasharray="{{ $lenVC }}, 100" stroke-dashoffset="-75" d="{{ $pathCarte }}" />@endif
                                        </svg>
                                        <span class="absolute inset-0 flex items-center justify-center text-sm font-bold text-white">{{ round($pctCarte) }}%</span>
                                    </div>
                                    <span class="text-slate-400 text-xs mt-0.5 whitespace-nowrap">Complétude du profil</span>
                                </div>
                            </div>
                        </div>
                        <div class="border border-t-0 border-slate-200 rounded-b-xl p-5 space-y-4">
                            <section>
                                <h4 class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-2">Contact</h4>
                                <ul class="space-y-1 text-slate-700 text-sm">
                                    <li><span class="text-slate-500">Email :</span> {{ auth()->user()->email }}</li>
                                    @if($profile->phone)<li><span class="text-slate-500">Tél :</span> {{ $profile->phone }}</li>@endif
                                    @if($profile->city || $profile->country)<li><span class="text-slate-500">Lieu :</span> {{ trim(implode(', ', array_filter([$profile->city, $profile->country]))) }}</li>@endif
                                </ul>
                            </section>
                            <div class="grid sm:grid-cols-2 gap-4">
                                <section>
                                    <h4 class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-2">Disponibilité & mobilité</h4>
                                    <ul class="space-y-1 text-slate-700 text-sm">
                                        @if($profile->availability)<li>{{ config('recruitment.availability')[$profile->availability] ?? $profile->availability }}</li>@endif
                                        @if($profile->mobility)<li>{{ config('recruitment.mobility')[$profile->mobility] ?? $profile->mobility }}</li>@endif
                                        @if(!$profile->availability && !$profile->mobility)<li class="text-slate-400">—</li>@endif
                                    </ul>
                                </section>
                                <section>
                                    <h4 class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-2">Parcours</h4>
                                    <ul class="space-y-1 text-slate-700 text-sm">
                                        @if($profile->experience_range)<li>{{ config('recruitment.experience_range')[$profile->experience_range] ?? $profile->experience_range }}</li>@endif
                                        @if($profile->education_level)<li>{{ config('recruitment.education_levels')[$profile->education_level] ?? $profile->education_level }}</li>@endif
                                        @if($profile->job_type_sought)<li>{{ config('recruitment.job_types')[$profile->job_type_sought] ?? $profile->job_type_sought }}</li>@endif
                                        @if(!$profile->experience_range && !$profile->education_level && !$profile->job_type_sought)<li class="text-slate-400">—</li>@endif
                                    </ul>
                                </section>
                            </div>
                            @php $skillsList = $profile->skills ?? []; $skillsList = array_filter($skillsList, function($s) { return !empty(trim($s['name'] ?? '')); }); @endphp
                            @if(count($skillsList) > 0)
                                <section>
                                    <h4 class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-2">Compétences</h4>
                                    <div class="flex flex-wrap gap-1.5">
                                        @foreach($skillsList as $s)
                                            <span class="bg-slate-100 text-slate-700 px-2.5 py-0.5 rounded text-xs">{{ $s['name'] ?? '' }}@if(isset($s['level']) && isset(config('recruitment.skill_levels')[$s['level']])) ({{ config('recruitment.skill_levels')[$s['level']] }})@endif</span>
                                        @endforeach
                                    </div>
                                </section>
                            @endif
                            @php $langsList = $profile->languages ?? []; $langsList = array_filter($langsList, function($l) { return !empty(trim($l['language'] ?? '')); }); @endphp
                            @if(count($langsList) > 0)
                                <section>
                                    <h4 class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-2">Langues</h4>
                                    <div class="flex flex-wrap gap-1.5">
                                        @foreach($langsList as $l)
                                            <span class="bg-slate-100 text-slate-700 px-2.5 py-0.5 rounded text-xs">{{ $l['language'] ?? '' }}@if(isset($l['level']) && isset(config('recruitment.language_levels')[$l['level']])) ({{ config('recruitment.language_levels')[$l['level']] }})@endif</span>
                                        @endforeach
                                    </div>
                                </section>
                            @endif
                            @if($profile->cv_path)
                                <section class="pt-2 border-t border-slate-100">
                                    <a href="{{ asset('storage/'.$profile->cv_path) }}" target="_blank" rel="noopener" class="text-sm text-emerald-600 hover:text-emerald-700 font-medium">Télécharger le CV (PDF)</a>
                                </section>
                            @endif
                        </div>
                    </article>
                @endif
            </div>
        </div>
    </div>
</div>

<script type="application/json" id="profile-skill-levels">@json(config('recruitment.skill_levels'))</script>
<script type="application/json" id="profile-language-levels">@json(config('recruitment.language_levels'))</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var modalCarte = document.getElementById('modal-carte-candidat');
    var btnOpen = document.getElementById('btn-open-carte-candidat');
    var btnClose = document.getElementById('btn-close-carte-candidat');
    var backdrop = document.getElementById('modal-carte-backdrop');
    if (btnOpen) btnOpen.addEventListener('click', function() { if (modalCarte) modalCarte.classList.remove('hidden'); });
    if (btnClose) btnClose.addEventListener('click', function() { if (modalCarte) modalCarte.classList.add('hidden'); });
    if (backdrop) backdrop.addEventListener('click', function() { if (modalCarte) modalCarte.classList.add('hidden'); });
    document.addEventListener('keydown', function(e) { if (e.key === 'Escape' && modalCarte && !modalCarte.classList.contains('hidden')) modalCarte.classList.add('hidden'); });

    var skillLevels = JSON.parse(document.getElementById('profile-skill-levels').textContent || '{}');
    var languageLevels = JSON.parse(document.getElementById('profile-language-levels').textContent || '{}');

    function skillRowHtml(i) {
        var opts = Object.entries(skillLevels).map(function(e) { return '<option value="'+e[0]+'">'+e[1]+'</option>'; }).join('');
        return '<div class="skill-row flex flex-wrap gap-2 items-end">' +
            '<input type="text" name="skills['+i+'][name]" placeholder="Ex: PHP, Excel…" class="flex-1 min-w-[120px] rounded-lg border-slate-300">' +
            '<select name="skills['+i+'][level]" class="w-48 rounded-lg border-slate-300">'+opts+'</select>' +
            '<button type="button" class="remove-skill text-slate-400 hover:text-red-600 p-2" title="Supprimer">✕</button></div>';
    }
    function languageRowHtml(i) {
        var opts = Object.entries(languageLevels).map(function(e) { return '<option value="'+e[0]+'">'+e[1]+'</option>'; }).join('');
        return '<div class="language-row flex flex-wrap gap-2 items-end">' +
            '<input type="text" name="languages['+i+'][language]" placeholder="Ex: Français, Anglais…" class="flex-1 min-w-[120px] rounded-lg border-slate-300">' +
            '<select name="languages['+i+'][level]" class="w-48 rounded-lg border-slate-300">'+opts+'</select>' +
            '<button type="button" class="remove-language text-slate-400 hover:text-red-600 p-2" title="Supprimer">✕</button></div>';
    }

    var skillsContainer = document.getElementById('skills-container');
    var languagesContainer = document.getElementById('languages-container');

    document.getElementById('add-skill').addEventListener('click', function() {
        var n = skillsContainer.querySelectorAll('.skill-row').length;
        skillsContainer.insertAdjacentHTML('beforeend', skillRowHtml(n));
        bindRemoveSkill();
    });
    document.getElementById('add-language').addEventListener('click', function() {
        var n = languagesContainer.querySelectorAll('.language-row').length;
        languagesContainer.insertAdjacentHTML('beforeend', languageRowHtml(n));
        bindRemoveLanguage();
    });

    function bindRemoveSkill() {
        skillsContainer.querySelectorAll('.remove-skill').forEach(function(btn) {
            btn.onclick = function() {
                var row = btn.closest('.skill-row');
                if (skillsContainer.querySelectorAll('.skill-row').length > 1) row.remove();
            };
        });
    }
    function bindRemoveLanguage() {
        languagesContainer.querySelectorAll('.remove-language').forEach(function(btn) {
            btn.onclick = function() {
                var row = btn.closest('.language-row');
                if (languagesContainer.querySelectorAll('.language-row').length > 1) row.remove();
            };
        });
    }
    bindRemoveSkill();
    bindRemoveLanguage();
});
</script>
@endsection
