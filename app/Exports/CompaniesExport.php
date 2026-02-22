<?php

namespace App\Exports;

use App\Models\Company;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CompaniesExport implements FromQuery, WithHeadings, WithMapping
{
    use Exportable;

    public function query()
    {
        return Company::withCount('jobOffers')->orderBy('id');
    }

    public function headings(): array
    {
        return ['ID', 'Nom', 'Email', 'Téléphone', 'Ville', 'Pays', 'Activée', 'Nb offres', 'Créé le'];
    }

    public function map($row): array
    {
        return [
            $row->id,
            $row->name,
            $row->email,
            $row->phone,
            $row->city,
            $row->country,
            $row->is_activated ? 'Oui' : 'Non',
            $row->job_offers_count ?? 0,
            $row->created_at?->format('d/m/Y H:i'),
        ];
    }
}
