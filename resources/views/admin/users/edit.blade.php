@extends('layouts.admin')

@section('title', 'Modifier utilisateur')

@section('content')
<h1 class="text-2xl font-bold mb-6">Modifier l'utilisateur</h1>
<form action="{{ route('admin.users.update', $user) }}" method="POST" class="bg-white rounded-xl border border-slate-200 p-6 max-w-xl">
    @csrf
    @method('PATCH')
    <div class="space-y-4">
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Nom</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full rounded-lg border-slate-300">
            @error('name')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Email</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="w-full rounded-lg border-slate-300">
            @error('email')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Rôle</label>
            <select name="role" required class="w-full rounded-lg border-slate-300">
                <option value="candidate" @selected(old('role', $user->role) === 'candidate')>Candidat</option>
                <option value="recruiter" @selected(old('role', $user->role) === 'recruiter')>Recruteur</option>
                <option value="admin" @selected(old('role', $user->role) === 'admin')>Admin</option>
            </select>
        </div>
        <div>
            <label class="inline-flex items-center">
                <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $user->is_active)) class="rounded border-slate-300">
                <span class="ml-2 text-sm text-slate-700">Compte actif</span>
            </label>
        </div>
    </div>
    <div class="mt-6 flex gap-2">
        <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg">Enregistrer</button>
        <a href="{{ route('admin.users.index') }}" class="bg-slate-200 hover:bg-slate-300 px-4 py-2 rounded-lg text-slate-700">Annuler</a>
    </div>
</form>
@endsection
