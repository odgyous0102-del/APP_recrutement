<?php

namespace Database\Factories;

use App\Models\Recruteur;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Recruteur>
 */
class RecruteurFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory()->create(['role' => 'recruteur']),
            'nom_entreprise' => $this->faker->company(),
            'secteur_activite' => $this->faker->randomElement(['Informatique', 'Finance', 'Santé', 'Éducation', 'Commerce']),
            'taille_entreprise' => $this->faker->randomElement(['startup', 'pme', 'grande_entreprise', 'multinationale']),
            'site_web' => $this->faker->url(),
            'description_ent' => $this->faker->paragraph(3),
            'is_verified' => true,
            'statut_verification' => 'valide',
            'contact_rh_email' => $this->faker->companyEmail(),
            'quota_offres' => $this->faker->numberBetween(1, 10),
        ];
    }
}
