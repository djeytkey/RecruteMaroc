<?php

namespace App\Http\Controllers\Recruteur;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $company = auth()->user()->company;
        $offers = $company ? $company->jobOffers()->with('recruitmentPack')->withCount('applications')->latest()->get() : collect();
        $totalApplications = $company ? $company->jobOffers()->withCount('applications')->get()->sum('applications_count') : 0;
        return view('recruteur.dashboard', compact('company', 'offers', 'totalApplications'));
    }
}
