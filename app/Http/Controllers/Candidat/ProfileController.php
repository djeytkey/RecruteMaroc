<?php

namespace App\Http\Controllers\Candidat;

use App\Http\Controllers\Controller;
use App\Models\Sector;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Propaganistas\LaravelPhone\Rules\Phone;
use Propaganistas\LaravelPhone\PhoneNumber;

class ProfileController extends Controller
{
    public function edit(): View
    {
        $profile = auth()->user()->candidateProfile?->load('sector');
        $sectors = Sector::orderBy('name')->get();
        return view('candidat.profile.edit', compact('profile', 'sectors'));
    }

    public function update(Request $request): RedirectResponse
    {
        $user = auth()->user();
        $profile = $user->candidateProfile;

        $countryIso = $this->countryNameToIso($request->input('country'));
        $phoneRules = ['nullable', 'string', 'max:30'];
        if ($request->filled('phone') && $countryIso) {
            $phoneRules[] = (new Phone)->country($countryIso);
        }

        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => $phoneRules,
            'country' => 'required|string|max:100',
            'city' => 'nullable|string|max:100',
            'mobility' => 'nullable|string|in:locale,nationale,internationale,teletravail_uniquement',
            'availability' => 'nullable|string|in:immediate,2_semaines,1_mois,3_mois,a_definir',
            'experience_range' => 'nullable|string|in:0-1,1-3,3-5,5-10,10-15,15+',
            'last_position' => 'nullable|string|max:255',
            'job_type_sought' => 'nullable|string|in:CDI,CDD,Freelance,Stage,Alternance,Temps partiel',
            'sector_id' => 'nullable|exists:sectors,id',
            'education_level' => 'nullable|string|in:Bac,Bac+2,Bac+3,Bac+5,Doctorat',
            'photo' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            'skills' => 'nullable|array',
            'skills.*.name' => 'nullable|string|max:255',
            'skills.*.level' => 'nullable|integer|in:25,50,75,100',
            'languages' => 'nullable|array',
            'languages.*.language' => 'nullable|string|max:100',
            'languages.*.level' => 'nullable|string|in:'.implode(',', array_keys(config('recruitment.language_levels'))),
        ], [
            'phone.phone' => 'Le numéro de téléphone n\'est pas valide pour le pays indiqué (ex. Maroc : 10 chiffres avec le 0).',
        ]);
        $validator->validate();
        $data = $validator->validated();

        $rawSkills = $request->input('skills', []);
        $rawLanguages = $request->input('languages', []);
        $data['skills'] = array_values(array_filter($rawSkills, fn($s) => !empty(trim($s['name'] ?? ''))));
        $data['languages'] = array_values(array_filter($rawLanguages, fn($l) => !empty(trim($l['language'] ?? ''))));
        unset($data['photo']);

        if (!empty($data['phone']) && $countryIso) {
            try {
                $data['phone'] = (new PhoneNumber($data['phone']))->formatE164();
            } catch (\Throwable $e) {
                // keep as-is if normalization fails
            }
        }

        if ($request->hasFile('photo')) {
            if ($profile?->photo_path) {
                Storage::disk('public')->delete($profile->photo_path);
            }
            $data['photo_path'] = $request->file('photo')->store('candidate_photos', 'public');
        }

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
        $profile->update(['completeness_percentage' => $this->computeCompleteness($profile->fresh())]);

        return redirect()->route('candidat.profile.edit')->with('success', 'CV mis à jour.');
    }

    private function computeCompleteness($profile): float
    {
        $fields = ['first_name', 'last_name', 'phone', 'country', 'city', 'mobility', 'availability', 'experience_range', 'last_position', 'job_type_sought', 'sector_id', 'education_level', 'cv_path', 'photo_path'];
        $filled = 0;
        foreach ($fields as $f) {
            $v = $profile->$f ?? null;
            if ($v !== null && $v !== '' && $v !== []) $filled++;
        }
        // Au moins une compétence saisie = 1 point
        $skills = is_array($profile->skills ?? null) ? $profile->skills : [];
        $hasSkills = count(array_filter($skills, function ($s) {
            return !empty(trim((string) ($s['name'] ?? '')));
        })) > 0;
        if ($hasSkills) $filled++;
        // Au moins une langue saisie = 1 point
        $languages = is_array($profile->languages ?? null) ? $profile->languages : [];
        $hasLanguages = count(array_filter($languages, function ($l) {
            return !empty(trim((string) ($l['language'] ?? '')));
        })) > 0;
        if ($hasLanguages) $filled++;
        $total = count($fields) + 2; // 13 champs + compétences + langues
        return round(($filled / $total) * 100, 2);
    }

    private function countryNameToIso(?string $country): ?string
    {
        if ($country === null || trim($country) === '') {
            return null;
        }
        $map = [
            'Maroc' => 'MA', 'France' => 'FR', 'Belgique' => 'BE', 'Suisse' => 'CH',
            'Canada' => 'CA', 'Allemagne' => 'DE', 'Espagne' => 'ES', 'Italie' => 'IT',
            'Royaume-Uni' => 'GB', 'États-Unis' => 'US', 'Algérie' => 'DZ', 'Tunisie' => 'TN',
            'Sénégal' => 'SN', 'Côte d\'Ivoire' => 'CI', 'Cameroun' => 'CM', 'Mauritanie' => 'MR',
            'Mali' => 'ML', 'Luxembourg' => 'LU', 'Pays-Bas' => 'NL',
        ];
        return $map[trim($country)] ?? null;
    }
}
