@extends('layouts.admin')

@section('title', 'Exports')

@section('content')
<h1 class="text-2xl font-bold mb-6">Exporter les données</h1>
<div class="bg-white rounded-xl border border-slate-200 p-6 max-w-xl">
    <p class="text-slate-600 mb-6">Téléchargez les données au format Excel (.xlsx).</p>
    <ul class="space-y-3">
        <li><a href="{{ route('admin.exports.users') }}" class="text-emerald-600 hover:underline font-medium">→ Utilisateurs</a></li>
        <li><a href="{{ route('admin.exports.companies') }}" class="text-emerald-600 hover:underline font-medium">→ Entreprises</a></li>
        <li><a href="{{ route('admin.exports.offers') }}" class="text-emerald-600 hover:underline font-medium">→ Offres d'emploi</a></li>
        <li><a href="{{ route('admin.exports.applications') }}" class="text-emerald-600 hover:underline font-medium">→ Candidatures</a></li>
        <li><a href="{{ route('admin.exports.rewards') }}" class="text-emerald-600 hover:underline font-medium">→ Récompenses</a></li>
    </ul>
</div>
@endsection
