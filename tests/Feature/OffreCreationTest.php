<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Recruteur;
use App\Models\Offre;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OffreCreationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test qu'un recruteur peut créer une offre.
     */
    public function test_recruteur_can_create_offre(): void
    {
        // Créer un utilisateur recruteur
        $user = User::factory()->create([
            'role' => 'recruteur'
        ]);

        // Créer le profil recruteur
        $recruteur = Recruteur::factory()->create([
            'user_id' => $user->id
        ]);

        // Données de l'offre
        $offreData = [
            'titre' => 'Développeur Full Stack',
            'type_contrat' => 'cdi',
            'lieu' => 'Paris',
            'salaire_min' => 45000,
            'salaire_max' => 65000,
            'description' => 'Nous recherchons un développeur expérimenté pour rejoindre notre équipe.',
            'competences' => 'PHP, JavaScript, React',
        ];

        // Simuler la connexion
        $this->actingAs($user);

        // Envoyer la requête POST pour créer l'offre
        $response = $this->post('/offres', $offreData);

        // Vérifier que la réponse est réussie
        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Offre publiée avec succès!'
        ]);

        // Vérifier que l'offre a été créée dans la base de données
        $this->assertDatabaseHas('offres', [
            'titre' => 'Développeur Full Stack',
            'recruteur_id' => $recruteur->id,
            'statut' => 'active'
        ]);
    }

    /**
     * Test que les données de l'offre sont validées.
     */
    public function test_offre_creation_validation(): void
    {
        // Créer un utilisateur recruteur
        $user = User::factory()->create([
            'role' => 'recruteur'
        ]);

        $recruteur = Recruteur::factory()->create([
            'user_id' => $user->id
        ]);

        // Simuler la connexion
        $this->actingAs($user);

        // Données invalides (titre manquant)
        $invalidData = [
            'type_contrat' => 'cdi',
            'lieu' => 'Paris',
            'description' => 'Description test',
            'competences' => 'PHP',
        ];

        // Envoyer la requête avec des données invalides
        $response = $this->post('/offres', $invalidData);

        // Vérifier que la réponse contient des erreurs de validation
        $response->assertStatus(422);
    }

    /**
     * Test que seul un recruteur peut créer une offre.
     */
    public function test_only_recruteur_can_create_offre(): void
    {
        // Créer un utilisateur candidat
        $user = User::factory()->create([
            'role' => 'candidat'
        ]);

        // Simuler la connexion
        $this->actingAs($user);

        // Données de l'offre
        $offreData = [
            'titre' => 'Développeur Full Stack',
            'type_contrat' => 'cdi',
            'lieu' => 'Paris',
            'description' => 'Description test',
            'competences' => 'PHP',
        ];

        // Envoyer la requête
        $response = $this->post('/offres', $offreData);

        // Le middleware recruteur devrait rediriger les non-recruteurs
        $response->assertRedirect();
    }

    /**
     * Test la relation entre offre et recruteur.
     */
    public function test_offre_recruteur_relationship(): void
    {
        // Créer les données de test
        $user = User::factory()->create(['role' => 'recruteur']);
        $recruteur = Recruteur::factory()->create(['user_id' => $user->id]);
        
        $offre = Offre::factory()->create([
            'recruteur_id' => $recruteur->id
        ]);

        // Vérifier la relation
        $this->assertEquals($recruteur->id, $offre->recruteur->id);
        $this->assertEquals($recruteur->nom_entreprise, $offre->recruteur->nom_entreprise);
    }
}
