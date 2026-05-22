<?php

namespace Database\Factories;

use App\Models\Offre;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Offre>
 */
class OffreFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'recruteur_id' => \App\Models\Recruteur::factory(),
            'titre' => $this->faker->jobTitle(),
            'description' => $this->faker->paragraph(5),
            'competences_req' => json_encode($this->faker->randomElements(['PHP', 'JavaScript', 'React', 'Laravel', 'Vue.js', 'Python', 'Django', 'MySQL', 'PostgreSQL'], 3)),
            'type_contrat' => $this->faker->randomElement(['cdi', 'cdd', 'stage', 'alternance', 'freelance']),
            'lieu' => $this->faker->city(),
            'teletravail' => $this->faker->randomElement(['non', 'partiel', 'total']),
            'salaire_min' => $this->faker->numberBetween(25000, 45000),
            'salaire_max' => $this->faker->numberBetween(45000, 80000),
            'experience_req' => $this->faker->randomElement(['debutant', 'junior', 'confirme', 'senior', 'expert']),
            'statut' => 'publie',
            'date_limite' => $this->faker->dateTimeBetween('now', '+3 months')->format('Y-m-d'),
            'nb_postes' => $this->faker->numberBetween(1, 5),
            'vues' => $this->faker->numberBetween(0, 1000),
        ];
    }
}
