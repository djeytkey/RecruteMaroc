@extends('layouts.admin')

@section('title', 'Entreprises')

@section('content')
<div class="flex flex-wrap items-center justify-between gap-4 mb-6">
    <h1 class="text-2xl font-bold">Entreprises</h1>
    <form action="{{ route('admin.companies.index') }}" method="GET" class="flex gap-2">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Recherche..." class="rounded-lg border-slate-300">
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
                <th class="p-3 font-medium">Offres</th>
                <th class="p-3 font-medium">Activée</th>
                <th class="p-3 font-medium">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($companies as $c)
            <tr class="border-b border-slate-100 hover:bg-slate-50">
                <td class="p-3">{{ $c->id }}</td>
                <td class="p-3">{{ $c->name }}</td>
                <td class="p-3">{{ $c->email }}</td>
                <td class="p-3">{{ $c->job_offers_count ?? 0 }}</td>
                <td class="p-3">{{ $c->is_activated ? 'Oui' : 'Non' }}</td>
                <td class="p-3"><a href="{{ route('admin.companies.edit', $c) }}" class="text-emerald-600 hover:underline">Modifier</a></td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $companies->withQueryString()->links() }}</div>
@endsection
