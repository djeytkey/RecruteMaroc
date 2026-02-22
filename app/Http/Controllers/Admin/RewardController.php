<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reward;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RewardController extends Controller
{
    public function index(Request $request): View
    {
        $query = Reward::with(['application.user', 'application.jobOffer.company'])->latest();
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        $rewards = $query->paginate(15);
        return view('admin.rewards.index', compact('rewards'));
    }

    public function update(Request $request, Reward $reward): RedirectResponse
    {
        $reward->update($request->validate([
            'status' => 'required|in:pending,processing,paid,cancelled',
            'iban' => 'nullable|string|max:34',
            'bank_name' => 'nullable|string|max:255',
        ]));
        if ($request->status === 'paid') {
            $reward->update(['paid_at' => now()]);
        }
        return redirect()->route('admin.rewards.index')->with('success', 'Récompense mise à jour.');
    }
}
