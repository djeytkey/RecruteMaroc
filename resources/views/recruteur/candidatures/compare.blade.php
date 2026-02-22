@extends('layouts.public')

@section('title', 'Comparer les candidatures')

@section('content')
<h1 class="text-2xl font-bold text-slate-800 mb-6">Comparaison des candidatures — {{ $jobOffer->title }}</h1>
<div class="overflow-x-auto">
    <table class="w-full border border-slate-200 rounded-xl overflow-hidden">
        <thead class="bg-slate-100">
            <tr>
                <th class="p-3 text-left font-medium text-slate-700">Critère</th>
                @foreach($applications as $app)
                    <th class="p-3 text-left font-medium text-slate-700">{{ $app->user->candidateProfile?->full_name ?? $app->user->name }} ({{ $app->compatibility_score }}%)</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($jobOffer->offerCriteria as $criterion)
                <tr class="border-t border-slate-200">
                    <td class="p-3 font-medium text-slate-700">{{ $criterion->label }}</td>
                    @foreach($applications as $app)
                        @php $ans = $app->applicationAnswers->firstWhere('offer_criterion_id', $criterion->id); @endphp
                        <td class="p-3 text-slate-600">{{ $ans ? ($ans->numeric_value ?? $ans->text_value) : '—' }}</td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<p class="mt-4"><a href="{{ route('recruteur.offres.candidatures', $jobOffer) }}" class="text-emerald-600 hover:underline">← Retour aux candidatures</a></p>
@endsection
