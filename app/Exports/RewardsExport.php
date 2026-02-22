<?php

namespace App\Exports;

use App\Models\Reward;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class RewardsExport implements FromQuery, WithHeadings, WithMapping
{
    use Exportable;

    public function query()
    {
        return Reward::with(['application.user', 'application.jobOffer.company'])->orderBy('id');
    }

    public function headings(): array
    {
        return ['ID', 'Candidat', 'Email', 'Offre', 'Entreprise', 'Montant MAD', 'Statut', 'Versée le', 'Créé le'];
    }

    public function map($row): array
    {
        return [
            $row->id,
            $row->application?->user?->name,
            $row->application?->user?->email,
            $row->application?->jobOffer?->title,
            $row->application?->jobOffer?->company?->name,
            $row->amount_mad,
            $row->status,
            $row->paid_at?->format('d/m/Y'),
            $row->created_at?->format('d/m/Y H:i'),
        ];
    }
}
