@extends('layouts.admin')

@section('title', 'Utilisateurs')

@section('content')
<div class="flex flex-wrap items-center justify-between gap-4 mb-6">
    <h1 class="text-2xl font-bold">Utilisateurs</h1>
    <form action="{{ route('admin.users.index') }}" method="GET" class="flex gap-2">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Recherche..." class="rounded-lg border-slate-300">
        <select name="role" class="rounded-lg border-slate-300">
            <option value="">Tous les rôles</option>
            <option value="candidate" @selected(request('role') === 'candidate')>Candidat</option>
            <option value="recruiter" @selected(request('role') === 'recruiter')>Recruteur</option>
            <option value="admin" @selected(request('role') === 'admin')>Admin</option>
        </select>
        <button type="submit" class="bg-slate-700 text-white px-4 py-2 rounded-lg text-sm">Filtrer</button>
    </form>
</div>
<div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
    <table class="w-full text-left">
        <thead class="bg-slate-50 border-b border-slate-200">
            <tr>
                <th class="p-3 font-medium">ID</th>
                <th class="p-3 font-medium">Nom</th>
                <th class="p-3 font-medium">Email</th>
                <th class="p-3 font-medium">Rôle</th>
                <th class="p-3 font-medium">Entreprise</th>
                <th class="p-3 font-medium">Actif</th>
                <th class="p-3 font-medium">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $u)
            <tr class="border-b border-slate-100 hover:bg-slate-50">
                <td class="p-3">{{ $u->id }}</td>
                <td class="p-3">{{ $u->name }}</td>
                <td class="p-3">{{ $u->email }}</td>
                <td class="p-3">{{ $u->role }}</td>
                <td class="p-3">{{ $u->company?->name }}</td>
                <td class="p-3">{{ $u->is_active ? 'Oui' : 'Non' }}</td>
                <td class="p-3"><a href="{{ route('admin.users.edit', $u) }}" class="text-emerald-600 hover:underline">Modifier</a></td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $users->withQueryString()->links() }}</div>
@endsection
