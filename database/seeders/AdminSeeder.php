<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Administrateur;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer l'utilisateur administrateur
        $user = User::create([
            'name' => 'Administrateur Principal',
            'email' => 'admin@recrutement.com',
            'password' => Hash::make('Admin123!'),
            'role' => 'administrateur',
            'is_active' => true,
        ]);

        // Créer le profil administrateur
        Administrateur::create([
            'user_id' => $user->id,
            'permissions' => json_encode(['users', 'offres', 'candidatures', 'settings']),
            'departement' => 'IT',
            'niveau_acces' => 'full',
            'can_manage_users' => true,
            'can_manage_offres' => true,
            'two_factor_enabled' => false,
        ]);

        $this->command->info('Administrateur créé avec succès!');
    }
}
