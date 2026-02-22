<?php

namespace Database\Seeders;

use App\Models\Sector;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SectorSeeder extends Seeder
{
    public function run(): void
    {
        $sectors = [
            'Aéronautique / Spatial', 'Agriculture / Environnement', 'Agroalimentaire',
            'Ameublement / décoration / Design', 'Architecture', 'Assurance / Courtage',
            'Audiovisuel', 'Automobile / Motos / cycles', 'Banque / Finance', 'BTP / Génie Civil',
            'Centre d\'appels', 'Chimie / Parachimie / Peintures', 'Commerce / Vente',
            'Communication / Evénementiel', 'Comptabilité / audit', 'Conseil / Etudes',
            'Cosmétique / Parfumerie / Luxe', 'Distribution', 'Edition / Imprimerie',
            'Electromécanique / Mécanique', 'Electronique', 'Electricité',
            'Enseignement / Formation', 'Energie', 'Finance / comptabilité', 'Ferroviaire',
            'Hôtellerie / Restauration', 'Immobilier / Promoteur / Agence', 'Import / Export / Négoce',
            'Informatique', 'Internet / Multimédia', 'Industrie', 'Juridique / Cabinet d\'avocat',
            'Management', 'Marketing / Communication', 'Métallurgie / Sidérurgie',
            'Nettoyage / Sécurité / gardiennage', 'Offshoring / Nearshoring', 'Pétrole / Gaz',
            'Pharmacie / Santé', 'Plasturgie', 'Presse', 'Recrutement / intérim', 'Santé',
            'Service public / Administration', 'Télécom', 'Textile / Cuir',
            'Tourisme / Voyage / Loisirs', 'Transport / Messagerie / Logistique', 'Autre industrie',
        ];
        foreach ($sectors as $name) {
            Sector::firstOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name]
            );
        }
    }
}
