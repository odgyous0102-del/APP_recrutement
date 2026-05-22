<?php

namespace App\Http\Controllers;

use App\Models\Offre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OffreController extends Controller
{
    public function __construct()
    {
        // Le middleware est géré dans les routes/web.php
    }

    /**
     * Store a newly created offer in storage.
     */
    public function store(Request $request)
    {
        try {
            // Vérifier l'authentification
            if (!Auth::check()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Utilisateur non authentifié'
                ], 401);
            }

            // Vérifier le profil recruteur
            $user = Auth::user();
            if (!$user->recruteur) {
                return response()->json([
                    'success' => false,
                    'message' => 'Profil recruteur non trouvé'
                ], 403);
            }

            // Validation des données
            // Validation selon la structure exacte de la migration
            $validated = $request->validate([
                // $table->string('titre')
                'titre' => 'required|string|max:255',
                
                // $table->text('description')
                'description' => 'required|string|min:10',
                
                // $table->json('competences_req')->nullable()
                'competences_req' => 'nullable|string',
                
                // $table->enum('type_contrat', ['cdi', 'cdd', 'stage', 'alternance', 'freelance'])
                'type_contrat' => 'required|in:cdi,cdd,stage,alternance,freelance',
                
                // $table->string('lieu')
                'lieu' => 'required|string|max:255',
                
                // $table->enum('teletravail', ['non', 'partiel', 'total'])->default('non')
                'teletravail' => 'nullable|in:non,partiel,total',
                
                // $table->decimal('salaire_min', 10, 2)->nullable()
                'salaire_min' => 'nullable|numeric|min:0',
                
                // $table->decimal('salaire_max', 10, 2)->nullable()
                'salaire_max' => 'nullable|numeric|min:0',
                
                // $table->enum('experience_req', ['debutant', 'junior', 'confirme', 'senior', 'expert'])
                'experience_req' => 'nullable|in:debutant,junior,confirme,senior,expert',
                
                // $table->enum('statut', ['brouillon', 'publie', 'archive'])->default('brouillon')
                'statut' => 'required|in:brouillon,publie,archive',
                
                // $table->date('date_limite')->nullable()
                'date_limite' => 'nullable|date|after:today',
                
                // $table->integer('nb_postes')->default(1)
                'nb_postes' => 'nullable|integer|min:1',
            ]);

            // Traiter les compétences JSON selon la migration
            $competencesArray = [];
            if (!empty($validated['competences_req'])) {
                if (is_string($validated['competences_req'])) {
                    $decoded = json_decode($validated['competences_req'], true);
                    if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                        $competencesArray = $decoded;
                    } else {
                        // Fallback: traiter comme chaîne séparée par des virgules
                        $competencesArray = array_map('trim', explode(',', $validated['competences_req']));
                        $competencesArray = array_filter($competencesArray, 'strlen');
                    }
                } elseif (is_array($validated['competences_req'])) {
                    $competencesArray = $validated['competences_req'];
                }
            }

            // Création de l'offre selon la migration exacte
            $offre = Offre::create([
                // $table->foreignId('recruteur_id')->constrained()->onDelete('cascade')
                'recruteur_id' => $user->recruteur->id,
                
                // $table->string('titre')
                'titre' => $validated['titre'],
                
                // $table->text('description')
                'description' => $validated['description'],
                
                // $table->json('competences_req')->nullable()
                'competences_req' => !empty($competencesArray) ? $competencesArray : null,
                
                // $table->enum('type_contrat', ['cdi', 'cdd', 'stage', 'alternance', 'freelance'])
                'type_contrat' => $validated['type_contrat'],
                
                // $table->string('lieu')
                'lieu' => $validated['lieu'],
                
                // $table->enum('teletravail', ['non', 'partiel', 'total'])->default('non')
                'teletravail' => $validated['teletravail'] ?? 'non',
                
                // $table->decimal('salaire_min', 10, 2)->nullable()
                'salaire_min' => $validated['salaire_min'],
                
                // $table->decimal('salaire_max', 10, 2)->nullable()
                'salaire_max' => $validated['salaire_max'],
                
                // $table->enum('experience_req', ['debutant', 'junior', 'confirme', 'senior', 'expert'])
                'experience_req' => $validated['experience_req'] ?? 'debutant',
                
                // $table->enum('statut', ['brouillon', 'publie', 'archive'])->default('brouillon')
                'statut' => $validated['statut'],
                
                // $table->date('date_limite')->nullable()
                'date_limite' => $validated['date_limite'] ?? now()->addDays(30),
                
                // $table->integer('nb_postes')->default(1)
                'nb_postes' => $validated['nb_postes'] ?? 1,
                
                // $table->integer('vues')->default(0)
                'vues' => 0,
            ]);

           /* return response()->json([
                'success' => true,
                'message' => 'Offre publiée avec succès!',
                'offre' => $offre
            ]);*/

            return redirect()->route('recruteur.dashboard')
    ->with('success', 'Offre publiée avec succès !');

        } catch (\Illuminate\Validation\ValidationException $e) {
            /*return response()->json([
                'success' => false,
                'message' => 'Erreur de validation',
                'errors' => $e->errors()
            ], 422);*/
            return redirect()->back()
    ->withErrors($e->validator)
    ->withInput();
        } catch (\Exception $e) {
            /* return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la publication: ' . $e->getMessage()
            ], 500);*/
            return redirect()->back()
        ->with('error', 'Erreur lors de la publication : ' . $e->getMessage());
        }
    }

    /**
     * Update the specified offer in storage.
     */
    public function update(Request $request, Offre $offre)
    {
        // Vérifier que le recruteur est bien le propriétaire de l'offre
        if ($offre->recruteur_id !== Auth::user()->recruteur->id) {
            return response()->json([
                'success' => false,
                'message' => 'Non autorisé à modifier cette offre'
            ], 403);
        }

        $validated = $request->validate([
            // Champs obligatoires
            'titre'          => 'required|string|max:255',
            'description'    => 'required|string|min:10',
            'type_contrat'   => 'required|in:cdi,cdd,stage,alternance,freelance',
            'lieu'           => 'required|string|max:255',
            // Champs nullable / avec default (migration)
            'competences_req'=> 'nullable|string',
            'teletravail'    => 'nullable|in:non,partiel,total',
            'salaire_min'    => 'nullable|numeric|min:0',
            'salaire_max'    => 'nullable|numeric|min:0',
            'experience_req' => 'nullable|in:debutant,junior,confirme,senior,expert',
            'statut'         => 'required|in:brouillon,publie,archive',
            'date_limite'    => 'nullable|date',
            'nb_postes'      => 'nullable|integer|min:1',
        ]);

        try {
            // Traiter competences_req en tableau JSON (cohérent avec la migration json)
            $competencesArray = [];
            if (!empty($validated['competences_req'])) {
                $decoded = json_decode($validated['competences_req'], true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                    $competencesArray = $decoded;
                } else {
                    $competencesArray = array_values(array_filter(array_map('trim', explode(',', $validated['competences_req'])), 'strlen'));
                }
            }

            $offre->update([
                'titre'          => $validated['titre'],
                'description'    => $validated['description'],
                'type_contrat'   => $validated['type_contrat'],
                'lieu'           => $validated['lieu'],
                'competences_req'=> !empty($competencesArray) ? $competencesArray : null,
                'teletravail'    => $validated['teletravail'] ?? 'non',
                'salaire_min'    => $validated['salaire_min'] ?? null,
                'salaire_max'    => $validated['salaire_max'] ?? null,
                'experience_req' => $validated['experience_req'] ?? 'debutant',
                'statut'         => $validated['statut'],
                'date_limite'    => $validated['date_limite'] ?? null,
                'nb_postes'      => $validated['nb_postes'] ?? 1,
            ]);

            return redirect()->route('recruteur.dashboard')->with('success', 'Offre mise à jour avec succès!');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors de la mise à jour de l\'offre: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified offer from storage.
     */
    public function destroy(Offre $offre)
    {
        // Vérifier que le recruteur est bien le propriétaire de l'offre
        if ($offre->recruteur_id !== Auth::user()->recruteur->id) {
            return response()->json([
                'success' => false,
                'message' => 'Non autorisé à supprimer cette offre'
            ], 403);
        }

        try {
            $offre->delete();

            return response()->json([
                'success' => true,
                'message' => 'Offre supprimée avec succès!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression de l\'offre: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get offers for the authenticated recruiter.
     */
    public function index()
    {
        $offres = Auth::user()->recruteur->offres()->with('candidatures')->get();
        
        return response()->json([
            'success' => true,
            'offres' => $offres
        ]);
    }

    /**
     * Display the specified offer.
     */
    public function show(Offre $offre)
    {
        // Vérifier que le recruteur est bien le propriétaire de l'offre
        if ($offre->recruteur_id !== Auth::user()->recruteur->id) {
            return response()->json([
                'success' => false,
                'message' => 'Non autorisé à voir cette offre'
            ], 403);
        }

        $offre->load('candidatures.candidat.user');

        return response()->json([
            'success' => true,
            'offre' => $offre
        ]);
    }
}
