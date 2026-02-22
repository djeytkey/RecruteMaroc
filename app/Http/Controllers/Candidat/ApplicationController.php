<?php

namespace App\Http\Controllers\Candidat;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\JobOffer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ApplicationController extends Controller
{
    public function create(JobOffer $jobOffer): View
    {
        if ($jobOffer->status !== 'published') abort(404);
        if ($jobOffer->applications()->where('user_id', auth()->id())->exists()) {
            return redirect()->route('job-offers.show', $jobOffer)->with('error', 'Vous avez déjà postulé.');
        }
        $jobOffer->load('offerCriteria');
        $profile = auth()->user()->candidateProfile;
        if (!$profile || !$profile->cv_path) {
            return redirect()->route('candidat.profile.edit')->with('error', 'Veuillez compléter votre profil et téléverser votre CV avant de postuler.');
        }
        return view('candidat.applications.create', compact('jobOffer'));
    }

    public function store(Request $request, JobOffer $jobOffer): RedirectResponse
    {
        if ($jobOffer->status !== 'published') abort(404);
        if ($jobOffer->applications()->where('user_id', auth()->id())->exists()) {
            return redirect()->route('job-offers.show', $jobOffer)->with('error', 'Vous avez déjà postulé.');
        }

        $criteria = $jobOffer->offerCriteria;
        $rules = [];
        foreach ($criteria as $c) {
            if ($c->type === 'quantitative') {
                $rules["criteria.{$c->id}"] = 'required|integer|in:25,50,75,100';
            } else {
                $rules["criteria.{$c->id}"] = 'nullable|string|max:1000';
            }
        }
        $request->validate($rules);

        $application = new Application([
            'job_offer_id' => $jobOffer->id,
            'user_id' => auth()->id(),
            'status' => 'pending',
        ]);

        $totalWeight = $criteria->sum('weight_percentage') ?: 100;
        $scores = [];
        $totalScore = 0;

        foreach ($criteria as $c) {
            $val = $request->input("criteria.{$c->id}");
            $contribution = 0;
            if ($c->type === 'quantitative' && $val !== null) {
                $contribution = (min((int) $val, $c->expected_level ?? 100) / 100) * ($c->weight_percentage / $totalWeight) * 100;
            } elseif ($c->type === 'declarative' && $val !== null) {
                $contribution = ($c->weight_percentage / $totalWeight) * 100;
            }
            $scores[$c->id] = round($contribution, 2);
            $totalScore += $contribution;
        }

        $application->compatibility_score = round($totalScore, 2);
        $application->criteria_scores = $scores;
        $application->save();

        foreach ($criteria as $c) {
            $val = $request->input("criteria.{$c->id}");
            $application->applicationAnswers()->create([
                'offer_criterion_id' => $c->id,
                'numeric_value' => $c->type === 'quantitative' ? (int) $val : null,
                'text_value' => $c->type === 'declarative' ? $val : null,
                'score_contribution' => $scores[$c->id] ?? 0,
            ]);
        }

        return redirect()->route('candidat.applications.index')->with('success', 'Candidature envoyée. Score de compatibilité : ' . $application->compatibility_score . '%');
    }
}
