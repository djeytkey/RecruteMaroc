<?php

namespace App\Http\Controllers;

use App\Models\JobOffer;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $offers = JobOffer::with(['company', 'recruitmentPack'])
            ->published()
            ->latest('published_at')
            ->take(8)
            ->get();
        return view('home', compact('offers'));
    }
}
