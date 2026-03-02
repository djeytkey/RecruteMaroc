@extends('layouts.admin')

@section('title', 'Paramètres')

@section('content')
<h1 class="text-2xl font-bold mb-6">Paramètres du système</h1>
<form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-xl border border-slate-200 p-6 max-w-xl">
    @csrf
    @method('PUT')
    <div class="space-y-6">
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Nom du système</label>
            <input type="text" name="system_name" value="{{ old('system_name', $settings->system_name) }}" placeholder="{{ config('app.name') }}" class="w-full rounded-lg border border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
            @error('system_name')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
        </div>

        <div x-data='{"newLogoUrl":null,"logoInitial":@json($settings->logo_url)}'>
            <label class="block text-sm font-medium text-slate-700 mb-1">Logo</label>
            <input type="file" name="logo" accept="image/jpeg,image/png,image/gif,image/webp,image/svg+xml" class="w-full text-sm text-slate-600 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200" x-ref="logoInput" @change="newLogoUrl = $refs.logoInput.files[0] ? URL.createObjectURL($refs.logoInput.files[0]) : null">
            @error('logo')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            <div class="mt-2 flex items-center gap-4" x-show="newLogoUrl || logoInitial">
                <p class="text-sm text-slate-500">Aperçu :</p>
                <img :src="newLogoUrl || logoInitial" alt="Logo" class="h-16 object-contain border border-slate-200 rounded-lg bg-white p-1">
            </div>
        </div>

        <div x-data='{"newFaviconUrl":null,"faviconInitial":@json($settings->favicon_url)}'>
            <label class="block text-sm font-medium text-slate-700 mb-1">Favicon</label>
            <input type="file" name="favicon" accept="image/x-icon,image/png,image/gif,.ico" class="w-full text-sm text-slate-600 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200" x-ref="faviconInput" @change="newFaviconUrl = $refs.faviconInput.files[0] ? URL.createObjectURL($refs.faviconInput.files[0]) : null">
            @error('favicon')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            <div class="mt-2 flex items-center gap-4" x-show="newFaviconUrl || faviconInitial">
                <p class="text-sm text-slate-500">Aperçu :</p>
                <img :src="newFaviconUrl || faviconInitial" alt="Favicon" class="h-20 w-20 object-contain border border-slate-200 rounded bg-white p-0.5">
            </div>
        </div>
    </div>
    <div class="mt-6 flex gap-2">
        <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg">Enregistrer</button>
        <a href="{{ route('admin.dashboard') }}" class="bg-slate-200 hover:bg-slate-300 px-4 py-2 rounded-lg text-slate-700">Annuler</a>
    </div>
</form>
@endsection
