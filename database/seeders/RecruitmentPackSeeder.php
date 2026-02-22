<?php

namespace Database\Seeders;

use App\Models\RecruitmentPack;
use Illuminate\Database\Seeder;

class RecruitmentPackSeeder extends Seeder
{
    public function run(): void
    {
        $packs = [
            [
                'slug' => 'essentiel',
                'name' => 'Pack Recrutement Essentiel',
                'price_mad' => 1500,
                'candidate_reward_mad' => 500,
                'publication_days' => 30,
                'badge_color' => '#22c55e',
                'cvtheque_consultations_per_month' => 0,
                'features' => [
                    'Analyse automatique du profil', 'Score de compatibilité (%)',
                    'Tri automatique des candidatures', 'Réponse automatique standard',
                    'Publication 30 jours', 'Gestion des candidatures', 'Tableau de bord simple',
                ],
            ],
            [
                'slug' => 'optimise',
                'name' => 'Pack Recrutement Optimisé',
                'price_mad' => 2500,
                'candidate_reward_mad' => 1000,
                'publication_days' => 45,
                'badge_color' => '#3b82f6',
                'cvtheque_consultations_per_month' => 20,
                'features' => [
                    'Score de compatibilité détaillé', 'Comparaison côte à côte (2 profils)',
                    'Choix des critères bloquants', 'Publication 45 jours',
                    'Mise en avant prioritaire', 'Filtres avancés', 'Reporting simple',
                ],
            ],
            [
                'slug' => 'strategique',
                'name' => 'Pack Recrutement Stratégique',
                'price_mad' => 4490,
                'candidate_reward_mad' => 1500,
                'publication_days' => 60,
                'badge_color' => '#8b5cf6',
                'cvtheque_consultations_per_month' => null,
                'features' => [
                    'Matching stratégique', 'Pondération personnalisée des critères',
                    'Comparaison multi-profils', 'Shortliste automatique',
                    'Publication 60 jours', 'Visibilité maximale', 'CVthèque illimitée',
                ],
            ],
        ];
        foreach ($packs as $pack) {
            RecruitmentPack::updateOrCreate(['slug' => $pack['slug']], $pack);
        }
    }
}
