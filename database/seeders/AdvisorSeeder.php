<?php

namespace Database\Seeders;

use App\Models\Advisor;
use Illuminate\Database\Seeder;

class AdvisorSeeder extends Seeder
{
    public function run(): void
    {
        $advisors = [
            [
                'name' => 'Jean Dupont',
                'specialization' => 'Investissement immobilier',
                'rating' => 4.8,
                'description' => 'Expert en investissement immobilier avec plus de 15 ans d\'expérience.',
                'is_public' => true,
            ],
            [
                'name' => 'Marie Laurent',
                'specialization' => 'Planification de retraite',
                'rating' => 4.9,
                'description' => 'Spécialisée dans la planification financière pour la retraite et la gestion de patrimoine.',
                'is_public' => true,
            ],
            [
                'name' => 'Pierre Martin',
                'specialization' => 'Investissement boursier',
                'rating' => 4.7,
                'description' => 'Expert en marchés financiers et stratégies d\'investissement.',
                'is_public' => true,
            ],
        ];

        foreach ($advisors as $advisor) {
            Advisor::create($advisor);
        }
    }
}
