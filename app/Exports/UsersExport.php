<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class UsersExport implements FromQuery, WithHeadings, WithMapping
{
    use Exportable;

    public function query()
    {
        return User::with('company')->orderBy('id');
    }

    public function headings(): array
    {
        return ['ID', 'Nom', 'Email', 'Rôle', 'Entreprise', 'Actif', 'Créé le'];
    }

    public function map($row): array
    {
        return [
            $row->id,
            $row->name,
            $row->email,
            $row->role,
            $row->company?->name,
            $row->is_active ? 'Oui' : 'Non',
            $row->created_at?->format('d/m/Y H:i'),
        ];
    }
}
