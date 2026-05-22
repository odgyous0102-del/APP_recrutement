<?php

// Test simple pour vérifier la création d'offres
require_once __DIR__ . '/vendor/autoload.php';

use App\Models\User;
use App\Models\Recruteur;
use App\Models\Offre;

echo "=== Test simple de création d'offres ===\n\n";

try {
    // 1. Créer un utilisateur recruteur
    $user = new User();
    $user->name = 'Test Recruteur';
    $user->email = 'recruteur' . time() . '@test.com';
    $user->password = bcrypt('password');
    $user->role = 'recruteur';
    $user->save();
    
    echo "✅ Utilisateur recruteur créé: " . $user->email . "\n";
    
    // 2. Créer le profil recruteur
    $recruteur = new Recruteur();
    $recruteur->user_id = $user->id;
    $recruteur->nom_entreprise = 'Entreprise Test';
    $recruteur->secteur_activite = 'Informatique';
    $recruteur->taille_entreprise = 'pme';
    $recruteur->site_web = 'https://example.com';
    $recruteur->description_ent = 'Description de l\'entreprise';
    $recruteur->is_verified = true;
    $recruteur->statut_verification = 'valide';
    $recruteur->contact_rh_email = 'rh@example.com';
    $recruteur->quota_offres = 5;
    $recruteur->save();
    
    echo "✅ Profil recruteur créé: " . $recruteur->nom_entreprise . "\n";
    
    // 3. Créer une offre
    $offre = new Offre();
    $offre->recruteur_id = $recruteur->id;
    $offre->titre = 'Développeur Full Stack Test';
    $offre->description = 'Nous recherchons un développeur expérimenté pour rejoindre notre équipe.';
    $offre->competences_req = json_encode(['PHP', 'JavaScript', 'React']);
    $offre->type_contrat = 'cdi';
    $offre->lieu = 'Paris';
    $offre->teletravail = 'non';
    $offre->salaire_min = 45000;
    $offre->salaire_max = 65000;
    $offre->experience_req = 'senior';
    $offre->statut = 'publie';
    $offre->date_limite = date('Y-m-d', strtotime('+30 days'));
    $offre->nb_postes = 1;
    $offre->vues = 0;
    $offre->save();
    
    echo "✅ Offre créée avec succès!\n";
    echo "   - ID: " . $offre->id . "\n";
    echo "   - Titre: " . $offre->titre . "\n";
    echo "   - Type: " . $offre->type_contrat . "\n";
    echo "   - Lieu: " . $offre->lieu . "\n";
    echo "   - Statut: " . $offre->statut . "\n";
    
    // 4. Vérifier les relations
    $offreWithRelations = Offre::with('recruteur')->find($offre->id);
    if ($offreWithRelations && $offreWithRelations->recruteur) {
        echo "✅ Relation Offre->Recruteur fonctionne\n";
        echo "   - Entreprise: " . $offreWithRelations->recruteur->nom_entreprise . "\n";
    }
    
    // 5. Compter les offres
    $totalOffres = Offre::count();
    echo "✅ Total d'offres dans la base: " . $totalOffres . "\n";
    
    echo "\n=== Test réussi ! ===\n";
    echo "La création d'offres fonctionne parfaitement.\n";
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
