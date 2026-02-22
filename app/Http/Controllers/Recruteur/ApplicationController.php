<?php

namespace App\Http\Controllers\Recruteur;

use App\Http\Controllers\Controller;
use App\Mail\CandidateRejectionMail;
use App\Mail\RewardNotificationMail;
use App\Models\Application;
use App\Models\JobOffer;
use App\Models\Reward;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class ApplicationController extends Controller
{
    public function index(JobOffer $jobOffer): View|RedirectResponse
    {
        if ($jobOffer->company_id !== auth()->user()->company_id) abort(404);
        $applications = $jobOffer->applications()->with(['user.candidateProfile'])->orderByDesc('compatibility_score')->paginate(15);
        return view('recruteur.candidatures.index', compact('jobOffer', 'applications'));
    }

    public function compare(Request $request, JobOffer $jobOffer): View|RedirectResponse
    {
        if ($jobOffer->company_id !== auth()->user()->company_id) abort(404);
        $ids = $request->input('ids', []);
        $applications = $jobOffer->applications()->with(['user.candidateProfile', 'applicationAnswers.offerCriterion'])->whereIn('id', $ids)->orderByDesc('compatibility_score')->get();
        $jobOffer->load('offerCriteria');
        return view('recruteur.candidatures.compare', compact('jobOffer', 'applications'));
    }

    public function action(Request $request, Application $application): RedirectResponse
    {
        if ($application->jobOffer->company_id !== auth()->user()->company_id) abort(404);

        $action = $request->validate(['action' => 'required|in:reject,on_hold,shortlisted,trial_period,recruit'])['action'];

        if ($action === 'reject') {
            $application->update(['status' => 'rejected', 'replied_at' => now()]);
            Mail::to($application->user->email)->send(new CandidateRejectionMail($application));
        } elseif ($action === 'on_hold') {
            $application->update(['status' => 'on_hold']);
        } elseif ($action === 'shortlisted') {
            $application->update(['status' => 'shortlisted']);
        } elseif ($action === 'trial_period') {
            $application->update(['status' => 'trial_period']);
        } elseif ($action === 'recruit') {
            $application->update(['status' => 'recruited', 'recruited_at' => now(), 'trial_validated_at' => now()]);
            $reward = Reward::create([
                'application_id' => $application->id,
                'amount_mad' => $application->jobOffer->recruitmentPack->candidate_reward_mad,
                'status' => 'pending',
            ]);
            Mail::to($application->user->email)->send(new RewardNotificationMail($reward));
        }

        return redirect()->back()->with('success', 'Action enregistrée.');
    }
}
