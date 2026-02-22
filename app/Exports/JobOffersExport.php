<?php

namespace App\Exports;

use App\Models\JobOffer;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class JobOffersExport implements FromQuery, WithHeadings, WithMapping
{
    use Exportable;

    public function query()
    {
        return JobOffer::with(['company', 'recruitmentPack'])->orderBy('id');
    }

    public function headings(): array
    {
        return ['ID', 'Titre', 'Entreprise', 'Pack', 'Type contrat', 'Statut', 'Payée', 'Publiée le', 'Créé le'];
    }

    public function map($row): array
    {
        return [
            $row->id,
            $row->title,
            $row->company?->name,
            $row->recruitmentPack?->name,
            $row->contract_type,
            $row->status,
            $row->paid_at ? 'Oui' : 'Non',
            $row->published_at?->format('d/m/Y'),
            $row->created_at?->format('d/m/Y H:i'),
        ];
    }
}
