<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobOffer;
use Illuminate\Http\Request;
use Illuminate\View\View;

class JobOfferController extends Controller
{
    public function index(Request $request): View
    {
        $query = JobOffer::with(['company', 'recruitmentPack'])->latest();
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('q')) {
            $query->where('title', 'like', '%' . $request->q . '%');
        }
        $offers = $query->paginate(15);
        return view('admin.offers.index', compact('offers'));
    }
}
