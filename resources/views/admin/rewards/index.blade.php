@extends('layouts.admin')

@section('title', 'Récompenses')

@section('content')
<div class="flex flex-wrap items-center justify-between gap-4 mb-6">
    <h1 class="text-2xl font-bold">Récompenses candidats</h1>
    <form action="{{ route('admin.rewards.index') }}" method="GET" class="flex gap-2">
        <select name="status" class="rounded-lg border-slate-300">
            <option value="">Tous statuts</option>
            <option value="pending" @selected(request('status') === 'pending')>En attente</option>
            <option value="processing" @selected(request('status') === 'processing')>En cours</option>
            <option value="paid" @selected(request('status') === 'paid')>Versée</option>
        </select>
        <button type="submit" class="bg-slate-700 text-white px-4 py-2 rounded-lg text-sm">Filtrer</button>
    </form>
</div>
<div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
    <table class="w-full text-left">
        <thead class="bg-slate-50 border-b border-slate-200">
            <tr>
                <th class="p-3 font-medium">ID</th>
                <th class="p-3 font-medium">Candidat</th>
                <th class="p-3 font-medium">Offre</th>
                <th class="p-3 font-medium">Entreprise</th>
                <th class="p-3 font-medium">Montant MAD</th>
                <th class="p-3 font-medium">Statut</th>
                <th class="p-3 font-medium">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rewards as $r)
            <tr class="border-b border-slate-100 hover:bg-slate-50">
                <td class="p-3">{{ $r->id }}</td>
                <td class="p-3">{{ $r->application?->user?->name }}<br><small class="text-slate-500">{{ $r->application?->user?->email }}</small></td>
                <td class="p-3">{{ $r->application?->jobOffer?->title }}</td>
                <td class="p-3">{{ $r->application?->jobOffer?->company?->name }}</td>
                <td class="p-3">{{ number_format($r->amount_mad, 0, ',', ' ') }}</td>
                <td class="p-3">{{ $r->status }}</td>
                <td class="p-3">
                    @if($r->status !== 'paid')
                    <form action="{{ route('admin.rewards.update', $r) }}" method="POST" class="inline">
                        @csrf
                        @method('PATCH')
                        <select name="status" class="rounded border-slate-300 text-sm" onchange="this.form.submit()">
                            <option value="pending" @selected($r->status === 'pending')>En attente</option>
                            <option value="processing" @selected($r->status === 'processing')>En cours</option>
                            <option value="paid" @selected($r->status === 'paid')>Versée</option>
                            <option value="cancelled" @selected($r->status === 'cancelled')>Annulée</option>
                        </select>
                    </form>
                    @else
                    Versée le {{ $r->paid_at?->format('d/m/Y') }}
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $rewards->withQueryString()->links() }}</div>
@endsection
