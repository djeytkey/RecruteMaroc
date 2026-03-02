<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Propaganistas\LaravelPhone\Rules\Phone;
use Propaganistas\LaravelPhone\PhoneNumber;

class CompanyController extends Controller
{
    public function index(Request $request): View
    {
        $query = Company::withCount('jobOffers', 'users')->latest();
        if ($request->filled('q')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->q . '%')
                    ->orWhere('email', 'like', '%' . $request->q . '%');
            });
        }
        $companies = $query->paginate(15);
        return view('admin.companies.index', compact('companies'));
    }

    public function edit(Company $company): View
    {
        return view('admin.companies.edit', compact('company'));
    }

    public function update(Request $request, Company $company): RedirectResponse
    {
        $countryIso = $this->countryNameToIso($request->input('country'));
        $phoneRules = ['nullable', 'string', 'max:30'];
        if ($request->filled('phone') && $countryIso) {
            $phoneRules[] = (new Phone)->country($countryIso);
        }

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => $phoneRules,
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'website' => 'nullable|url|max:255',
        ], [
            'phone.phone' => 'Le numéro de téléphone n\'est pas valide pour le pays indiqué (ex. Maroc : 10 chiffres avec le 0).',
        ]);

        if (!empty($data['phone']) && $countryIso) {
            try {
                $data['phone'] = (new PhoneNumber($data['phone']))->formatE164();
            } catch (\Throwable $e) {
                // keep as-is
            }
        }
        $data['is_activated'] = $request->boolean('is_activated');
        $company->update($data);
        return redirect()->route('admin.companies.index')->with('success', 'Entreprise mise à jour.');
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

