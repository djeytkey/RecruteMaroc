<?php

namespace App\Http\Controllers\Candidat;

use App\Http\Controllers\Controller;
use App\Models\Sector;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function edit(): View
    {
        $profile = auth()->user()->candidateProfile;
        $sectors = Sector::orderBy('name')->get();
        return view('candidat.profile.edit', compact('profile', 'sectors'));
    }

    public function update(Request $request): RedirectResponse
    {
        $user = auth()->user();
        $profile = $user->candidateProfile;

        $data = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:30',
            'country' => 'required|string|max:100',
            'city' => 'nullable|string|max:100',
            'mobility' => 'nullable|string|in:locale,nationale,internationale,teletravail_uniquement',
            'availability' => 'nullable|string|in:immediate,2_semaines,1_mois,3_mois,a_definir',
            'experience_range' => 'nullable|string|in:0-1,1-3,3-5,5-10,10-15,15+',
            'last_position' => 'nullable|string|max:255',
            'job_type_sought' => 'nullable|string|in:CDI,CDD,Freelance,Stage,Alternance,Temps partiel',
            'sector_id' => 'nullable|exists:sectors,id',
            'education_level' => 'nullable|string|in:Bac,Bac+2,Bac+3,Bac+5,Doctorat',
            'skills' => 'nullable|array',
            'skills.*.name' => 'required_with:skills|string|max:255',
            'skills.*.level' => 'required_with:skills|integer|in:25,50,75,100',
            'languages' => 'nullable|array',
            'languages.*.language' => 'required_with:languages|string|max:100',
            'languages.*.level' => 'required_with:languages|string|max:50',
        ]);

        $data['skills'] = $request->input('skills', []);
        $data['languages'] = $request->input('languages', []);

        if ($profile) {
            $profile->update($data);
            $profile->update(['completeness_percentage' => $this->computeCompleteness($profile->fresh())]);
        } else {
            $p = $user->candidateProfile()->create(array_merge($data, ['user_id' => $user->id]));
            $p->update(['completeness_percentage' => $this->computeCompleteness($p)]);
        }

        return redirect()->route('candidat.profile.edit')->with('success', 'Profil mis à jour.');
    }

    public function uploadCv(Request $request): RedirectResponse
    {
        $request->validate(['cv' => 'required|file|mimes:pdf|max:5120']);

        $user = auth()->user();
        $profile = $user->candidateProfile;
        if (!$profile) {
            return redirect()->route('candidat.profile.edit')->with('error', 'Complétez d\'abord votre profil.');
        }

        if ($profile->cv_path) {
            Storage::disk('public')->delete($profile->cv_path);
        }
        $path = $request->file('cv')->store('cvs', 'public');
        $profile->update(['cv_path' => $path]);

        return redirect()->route('candidat.profile.edit')->with('success', 'CV mis à jour.');
    }

    private function computeCompleteness($profile): float
    {
        $fields = ['first_name', 'last_name', 'phone', 'country', 'city', 'mobility', 'availability', 'experience_range', 'last_position', 'job_type_sought', 'sector_id', 'education_level', 'skills', 'languages', 'cv_path'];
        $filled = 0;
        foreach ($fields as $f) {
            $v = $profile->$f ?? null;
            if ($v !== null && $v !== '' && $v !== []) $filled++;
        }
        return round(($filled / count($fields)) * 100, 2);
    }
}
