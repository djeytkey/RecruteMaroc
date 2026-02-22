@extends('layouts.admin')

@section('title', 'Modifier entreprise')

@section('content')
<h1 class="text-2xl font-bold mb-6">Modifier l'entreprise</h1>
<form action="{{ route('admin.companies.update', $company) }}" method="POST" class="bg-white rounded-xl border border-slate-200 p-6 max-w-xl">
    @csrf
    @method('PATCH')
    <div class="space-y-4">
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Nom</label>
            <input type="text" name="name" value="{{ old('name', $company->name) }}" required class="w-full rounded-lg border-slate-300">
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Email</label>
            <input type="email" name="email" value="{{ old('email', $company->email) }}" required class="w-full rounded-lg border-slate-300">
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Téléphone</label>
            <input type="text" name="phone" value="{{ old('phone', $company->phone) }}" class="w-full rounded-lg border-slate-300">
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Ville</label>
            <input type="text" name="city" value="{{ old('city', $company->city) }}" class="w-full rounded-lg border-slate-300">
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Pays</label>
            <input type="text" name="country" value="{{ old('country', $company->country) }}" class="w-full rounded-lg border-slate-300">
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Site web</label>
            <input type="url" name="website" value="{{ old('website', $company->website) }}" class="w-full rounded-lg border-slate-300">
        </div>
        <div>
            <label class="inline-flex items-center">
                <input type="checkbox" name="is_activated" value="1" @checked(old('is_activated', $company->is_activated)) class="rounded border-slate-300">
                <span class="ml-2 text-sm text-slate-700">Entreprise activée</span>
            </label>
        </div>
    </div>
    <div class="mt-6 flex gap-2">
        <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg">Enregistrer</button>
        <a href="{{ route('admin.companies.index') }}" class="bg-slate-200 hover:bg-slate-300 px-4 py-2 rounded-lg text-slate-700">Annuler</a>
    </div>
</form>
@endsection
