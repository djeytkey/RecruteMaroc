@extends('layouts.admin')

@section('title', 'Offres d\'emploi')

@section('content')
<div class="flex flex-wrap items-center justify-between gap-4 mb-6">
    <h1 class="text-2xl font-bold">Offres d'emploi</h1>
    <form action="{{ route('admin.offers.index') }}" method="GET" class="flex gap-2">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Titre..." class="rounded-lg border-slate-300">
        <select name="status" class="rounded-lg border-slate-300">
            <option value="">Tous statuts</option>
            <option value="draft" @selected(request('status') === 'draft')>Brouillon</option>
            <option value="published" @selected(request('status') === 'published')>Publiée</option>
            <option value="closed" @selected(request('status') === 'closed')>Clôturée</option>
        </select>
        <button type="submit" class="bg-slate-700 text-white px-4 py-2 rounded-lg text-sm">Filtrer</button>
    </form>
</div>
<div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
    <table class="w-full text-left">
        <thead class="bg-slate-50 border-b border-slate-200">
            <tr>
                <th class="p-3 font-medium">ID</th>
                <th class="p-3 font-medium">Titre</th>
                <th class="p-3 font-medium">Entreprise</th>
                <th class="p-3 font-medium">Pack</th>
                <th class="p-3 font-medium">Statut</th>
                <th class="p-3 font-medium">Payée</th>
                <th class="p-3 font-medium">Publiée le</th>
            </tr>
        </thead>
        <tbody>
            @foreach($offers as $o)
            <tr class="border-b border-slate-100 hover:bg-slate-50">
                <td class="p-3">{{ $o->id }}</td>
                <td class="p-3">{{ $o->title }}</td>
                <td class="p-3">{{ $o->company?->name }}</td>
                <td class="p-3">{{ $o->recruitmentPack?->name }}</td>
                <td class="p-3">{{ $o->status }}</td>
                <td class="p-3">{{ $o->paid_at ? 'Oui' : 'Non' }}</td>
                <td class="p-3">{{ $o->published_at?->format('d/m/Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $offers->withQueryString()->links() }}</div>
@endsection
