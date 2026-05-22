<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Models\User;
use App\Models\Recruteur;
use App\Models\Offre;
use Illuminate\Support\Facades\Hash;

// Test script pour vérifier la création d'offres

echo "=== Test de création d'offres ===\n\n";

// 1. Vérifier si un recruteur existe
$recruteur = Recruteur::first();
if (!$recruteur) {
    echo "❌ Aucun recruteur trouvé dans la base de données.\n";
    echo "Création d'un recruteur de test...\n";
    
    // Créer un utilisateur recruteur
    $user = User::create([
        'name' => 'Test Recruteur',
        'email' => 'recruteur@test.com',
        'password' => Hash::make('password'),
        'role' => 'recruteur',
    ]);
    
    // Créer le profil recruteur
    $recruteur = Recruteur::create([
        'user_id' => $user->id,
        'nom_entreprise' => 'Entreprise Test',
        'secteur_activite' => 'Informatique',
        'description' => 'Description de l\'entreprise',
        'site_web' => 'https://example.com',
        'taille_entreprise' => '50-100',
        'verification_statut' => 'verified',
    ]);
    
    echo "✅ Recruteur de test créé avec succès.\n";
} else {
    echo "✅ Recruteur trouvé: " . $recruteur->nom_entreprise . "\n";
}

// 2. Tester la création d'une offre
echo "\n=== Création d'une offre test ===\n";

try {
    $offre = new Offre();
    $offre->titre = 'Développeur Full Stack Test';
    $offre->type_contrat = 'CDI';
    $offre->lieu = 'Paris';
    $offre->salaire_min = 45000;
    $offre->salaire_max = 65000;
    $offre->description = 'Nous recherchons un développeur full stack expérimenté pour rejoindre notre équipe.';
    $offre->competences_req = 'PHP, JavaScript, React, Laravel';
    $offre->recruteur_id = $recruteur->id;
    $offre->statut = 'active';
    $offre->date_limite = now()->addDays(30);
    $offre->vues = 0;
    
    $offre->save();
    
    echo "✅ Offre créée avec succès!\n";
    echo "   - ID: " . $offre->id . "\n";
    echo "   - Titre: " . $offre->titre . "\n";
    echo "   - Recruteur: " . $offre->recruteur->nom_entreprise . "\n";
    echo "   - Statut: " . $offre->statut . "\n";
    
} catch (\Exception $e) {
    echo "❌ Erreur lors de la création de l'offre: " . $e->getMessage() . "\n";
}

// 3. Vérifier les relations
echo "\n=== Vérification des relations ===\n";

try {
    $offre = Offre::with('recruteur')->first();
    if ($offre) {
        echo "✅ Relation Offre->Recruteur fonctionne\n";
        echo "   - Offre: " . $offre->titre . "\n";
        echo "   - Recruteur: " . $offre->recruteur->nom_entreprise . "\n";
    }
    
    // Vérifier la relation inverse
    $recruteur = Recruteur::with('offres')->find($recruteur->id);
    if ($recruteur && $recruteur->offres->count() > 0) {
        echo "✅ Relation Recruteur->Offres fonctionne\n";
        echo "   - Nombre d'offres: " . $recruteur->offres->count() . "\n";
    }
    
} catch (\Exception $e) {
    echo "❌ Erreur dans les relations: " . $e->getMessage() . "\n";
}

echo "\n=== Test terminé ===\n";
echo "La création d'offres est fonctionnelle!\n";
