<?php

return [
    'mobility' => [
        'locale' => 'Locale',
        'nationale' => 'Nationale',
        'internationale' => 'Internationale',
        'teletravail_uniquement' => 'Télétravail uniquement',
    ],
    'availability' => [
        'immediate' => 'Immédiate',
        '2_semaines' => 'Sous 2 semaines',
        '1_mois' => 'Sous 1 mois',
        '3_mois' => 'Sous 3 mois',
        'a_definir' => 'À définir',
    ],
    'experience_range' => [
        '0-1' => '0-1 an',
        '1-3' => '1-3 ans',
        '3-5' => '3-5 ans',
        '5-10' => '5-10 ans',
        '10-15' => '10-15 ans',
        '15+' => '+15 ans',
    ],
    'job_types' => [
        'CDI' => 'CDI',
        'CDD' => 'CDD',
        'Freelance' => 'Freelance',
        'Stage' => 'Stage',
        'Alternance' => 'Alternance',
        'Temps partiel' => 'Temps partiel',
    ],
    'skill_levels' => [
        25 => 'Débutant (25%)',
        50 => 'Intermédiaire (50%)',
        75 => 'Avancé (75%)',
        100 => 'Expert (100%)',
    ],
    'education_levels' => [
        'Bac' => 'Bac',
        'Bac+2' => 'Bac+2',
        'Bac+3' => 'Bac+3',
        'Bac+5' => 'Bac+5',
        'Doctorat' => 'Doctorat',
    ],
    'language_levels' => [
        'debutant' => 'Débutant',
        'intermediaire' => 'Intermédiaire',
        'professionnel' => 'Professionnel',
        'bilingue' => 'Bilingue',
        'langue_maternelle' => 'Langue maternelle',
    ],
    'send_activation_email' => env('SEND_RECRUITER_ACTIVATION_EMAIL', false),

    'application_status_labels' => [
        'pending' => 'En attente',
        'shortlisted' => 'Sélectionné',
        'rejected' => 'Refusé',
        'on_hold' => 'En attente',
        'trial_period' => 'Période d\'essai',
        'trial_validated' => 'Période d\'essai validée',
        'recruited' => 'Recruté',
        'reward_pending' => 'Récompense en attente',
        'reward_paid' => 'Récompense versée',
    ],
];
