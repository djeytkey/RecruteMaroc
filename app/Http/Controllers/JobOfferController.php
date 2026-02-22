<?php

namespace App\Http\Controllers;

use App\Models\JobOffer;
use App\Models\Sector;
use Illuminate\Http\Request;
use Illuminate\View\View;

class JobOfferController extends Controller
{
    public function index(Request $request): View
    {
        $query = JobOffer::with(['company', 'recruitmentPack'])->published();

        if ($request->filled('q')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->q . '%')
                    ->orWhere('description', 'like', '%' . $request->q . '%');
            });
        }
        if ($request->filled('sector')) {
            $query->where('sector_id', $request->sector);
        }
        if ($request->filled('contract_type')) {
            $query->where('contract_type', $request->contract_type);
        }
        if ($request->filled('location')) {
            $query->where('location', 'like', '%' . $request->location . '%');
        }

        $offers = $query->latest('published_at')->paginate(12);
        $sectors = Sector::orderBy('name')->get();

        return view('job-offers.index', compact('offers', 'sectors'));
    }

    public function show(JobOffer $jobOffer): View
    {
        if ($jobOffer->status !== 'published') {
            abort(404);
        }
        $jobOffer->load(['company', 'recruitmentPack', 'offerCriteria']);
        return view('job-offers.show', compact('jobOffer'));
    }
}
