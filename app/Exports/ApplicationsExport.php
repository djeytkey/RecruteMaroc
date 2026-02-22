<?php

namespace App\Exports;

use App\Models\Application;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ApplicationsExport implements FromQuery, WithHeadings, WithMapping
{
    use Exportable;

    public function query()
    {
        return Application::with(['user', 'jobOffer.company'])->orderBy('id');
    }

    public function headings(): array
    {
        return ['ID', 'Offre', 'Entreprise', 'Candidat', 'Email', 'Score %', 'Statut', 'Date'];
    }

    public function map($row): array
    {
        return [
            $row->id,
            $row->jobOffer?->title,
            $row->jobOffer?->company?->name,
            $row->user?->name,
            $row->user?->email,
            $row->compatibility_score,
            $row->status,
            $row->created_at?->format('d/m/Y H:i'),
        ];
    }
}
