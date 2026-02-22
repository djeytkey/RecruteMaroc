@extends('layouts.public')

@section('title', 'Candidatures - ' . $jobOffer->title)

@section('content')
<div class="mb-6 flex flex-wrap items-center justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Candidatures</h1>
        <p class="text-slate-600">{{ $jobOffer->title }} · {{ $jobOffer->company->name }}</p>
    </div>
    <a href="{{ route('recruteur.offres.show', $jobOffer) }}" class="text-emerald-600 hover:underline">Retour à l'offre</a>
</div>

<div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
    <table class="w-full text-left">
        <thead class="bg-slate-50 border-b border-slate-200">
            <tr>
                <th class="p-3 font-medium text-slate-700">Candidat</th>
                <th class="p-3 font-medium text-slate-700">Score</th>
                <th class="p-3 font-medium text-slate-700">Statut</th>
                <th class="p-3 font-medium text-slate-700">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($applications as $app)
                <tr class="border-b border-slate-100 hover:bg-slate-50">
                    <td class="p-3">
                        @php $profile = $app->user->candidateProfile; @endphp
                        <span class="font-medium text-slate-800">{{ $profile ? $profile->full_name : $app->user->name }}</span>
                        <p class="text-sm text-slate-500">{{ $app->user->email }}</p>
                    </td>
                    <td class="p-3">{{ $app->compatibility_score ?? '—' }}%</td>
                    <td class="p-3">{{ config('recruitment.application_status_labels')[$app->status] ?? $app->status }}</td>
                    <td class="p-3">
                        @if($profile?->cv_path)
                            <a href="{{ asset('storage/'.$profile->cv_path) }}" target="_blank" class="text-sm text-emerald-600 hover:underline">CV</a>
                        @endif
                        <form action="{{ route('recruteur.candidatures.action', $app) }}" method="POST" class="inline ml-2">
                            @csrf
                            <input type="hidden" name="action" value="shortlisted">
                            <button type="submit" class="text-sm text-blue-600 hover:underline">Sélectionner</button>
                        </form>
                        <form action="{{ route('recruteur.candidatures.action', $app) }}" method="POST" class="inline ml-2">
                            @csrf
                            <input type="hidden" name="action" value="reject">
                            <button type="submit" class="text-sm text-red-600 hover:underline">Refuser</button>
                        </form>
                        <form action="{{ route('recruteur.candidatures.action', $app) }}" method="POST" class="inline ml-2">
                            @csrf
                            <input type="hidden" name="action" value="recruit">
                            <button type="submit" class="text-sm text-emerald-600 hover:underline font-medium">Recruter</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" class="p-6 text-slate-500 text-center">Aucune candidature pour cette offre.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $applications->links() }}</div>
@endsection
