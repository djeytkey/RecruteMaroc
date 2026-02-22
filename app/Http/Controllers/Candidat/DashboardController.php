<?php

namespace App\Http\Controllers\Candidat;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();
        $profile = $user->candidateProfile;
        $applications = $user->applications()->with(['jobOffer.company', 'jobOffer.recruitmentPack'])->latest()->take(10)->get();
        return view('candidat.dashboard', compact('profile', 'applications'));
    }

    public function applications(): View
    {
        $applications = auth()->user()->applications()->with(['jobOffer.company', 'jobOffer.recruitmentPack'])->latest()->paginate(15);
        return view('candidat.applications.index', compact('applications'));
    }
}
