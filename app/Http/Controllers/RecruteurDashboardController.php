<?php

namespace App\Http\Controllers;

use App\Models\Offre;
use App\Models\Candidature;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RecruteurDashboardController extends Controller
{
    /**
     * Affiche le tableau de bord du recruteur avec les données dynamiques
     */
    public function index()
    {
        $recruteur = Auth::user()->recruteur;

        // Récupérer toutes les offres du recruteur depuis la base de données
        $offres = Offre::where('recruteur_id', $recruteur->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // Statistiques
        $totalOffres = $offres->count();
        $offresActives = $offres->where('statut', 'publie')->count();

        // Récupérer les candidatures pour ces offres avec les relations nécessaires
        $offreIds = $offres->pluck('id');
        $candidatures = Candidature::whereIn('offre_id', $offreIds)
            ->with(['candidat.user', 'candidat.experiences', 'candidat.formations', 'offre'])
            ->orderBy('created_at', 'desc')
            ->get();

        $totalCandidatures = $candidatures->count();

        // Récupérer les entretiens à venir avec les relations nécessaires pour la vue
        $entretiens = \App\Models\Entretien::whereIn('candidature_id', $candidatures->pluck('id'))
            ->with(['candidature.candidat.user', 'candidature.offre', 'evaluations'])
            ->where('date_entretien', '>=', now())
            ->orderBy('date_entretien', 'asc')
            ->get();
        $totalEntretiens = $entretiens->count();

        // Messages pour la messagerie
        $messages = \App\Models\Message::where('expediteur_id', Auth::id())
            ->orWhere('destinataire_id', Auth::id())
            ->with(['expediteur', 'destinataire', 'candidature.offre', 'candidature.candidat.user'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Simulation de taux de conversion (Candidatures acceptées / Total)
        $candidaturesAcceptees = $candidatures->where('statut', 'acceptee')->count();
        $tauxConversion = $totalCandidatures > 0 ? round(($candidaturesAcceptees / $totalCandidatures) * 100) : 0;

        // Groupement des évaluations par offre
        $offresEvaluations = Offre::where('recruteur_id', $recruteur->id)
            ->with(['candidatures.entretiens.evaluations', 'candidatures.candidat.user'])
            ->get();

        // Analytics - Candidatures par mois (6 derniers mois)
        $candidaturesDerniersMois = Candidature::whereIn('offre_id', $offreIds)
            ->where('created_at', '>=', now()->subMonths(5)->startOfMonth())
            ->get()
            ->groupBy(function ($val) {
                return \Carbon\Carbon::parse($val->created_at)->format('Y-m');
            });

        $monthsLabels = [];
        $applicationsData = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $key = $date->format('Y-m');
            $monthsLabels[] = $date->translatedFormat('M'); // 'Jan', 'Fév', etc.
            $applicationsData[] = isset($candidaturesDerniersMois[$key]) ? $candidaturesDerniersMois[$key]->count() : 0;
        }

        // Analytics - Répartition par statut
        $candidaturesStatus = [
            $candidatures->where('statut', 'en_attente')->count() + $candidatures->whereNull('statut')->count(), // Nouveau / En attente
            $candidatures->where('statut', 'en_cours')->count(), // Entretien / En cours
            $candidatures->where('statut', 'acceptee')->count(), // Accepté
            $candidatures->where('statut', 'refusee')->count(),  // Refusé
        ];

        return view('dashboard-recruteur', compact(
            'offres',
            'candidatures',
            'entretiens',
            'totalOffres',
            'offresActives',
            'totalCandidatures',
            'totalEntretiens',
            'tauxConversion',
            'offresEvaluations',
            'monthsLabels',
            'applicationsData',
            'candidaturesStatus',
            'messages'
        ));
    }

    public function updateEvaluation(Request $request, Candidature $candidature)
    {
        $recruteur = Auth::user()->recruteur;

        if (!$recruteur || $candidature->offre->recruteur_id !== $recruteur->id) {
            abort(403, 'Action non autorisee.');
        }

        $validated = $request->validate([
            'statut' => 'nullable|in:en_attente,en_cours,acceptee,refusee',
            'etape_recrutement' => 'nullable|in:candidature,entretien_telephonique,entretien_technique,entretien_final,decision',
            'score_matching' => 'nullable|integer|min:0|max:100',
            'notes_rh' => 'nullable|string',
        ]);

        $candidature->update(array_filter(
            $validated,
            fn($value) => $value !== null && $value !== ''
        ));

        // Envoyer une notification au candidat si le statut ou l'étape a changé
        if (isset($validated['statut']) || isset($validated['etape_recrutement'])) {
            $msg = "Votre candidature pour le poste : " . $candidature->offre->titre . " a été mise à jour.";
            if (isset($validated['statut'])) {
                $statuts = [
                    'en_attente' => 'en attente',
                    'en_cours' => 'en cours d\'examen',
                    'acceptee' => 'acceptée',
                    'refusee' => 'refusée'
                ];
                $msg .= " Nouveau statut : " . ($statuts[$validated['statut']] ?? $validated['statut']) . ".";
            }

            Notification::create([
                'user_id' => $candidature->candidat->user_id,
                'type' => 'statut_candidature_mis_a_jour',
                'titre' => 'Mise à jour de votre candidature',
                'message' => $msg,
                'data' => [
                    'candidature_id' => $candidature->id,
                    'statut' => $validated['statut'] ?? $candidature->statut,
                    'etape' => $validated['etape_recrutement'] ?? $candidature->etape_recrutement,
                ],
            ]);
        }

        return back()->with('success', 'Candidature mise a jour avec succes.');
    }

    /**
     * Marque une candidature comme lue (en_cours) via AJAX
     */
    public function markCandidatureAsRead(\App\Models\Candidature $candidature)
    {
        $recruteur = Auth::user()->recruteur;
        
        if (!$recruteur || $candidature->offre->recruteur_id !== $recruteur->id) {
            return response()->json(['success' => false, 'message' => 'Action non autorisée.'], 403);
        }

        if (in_array($candidature->statut, ['en_attente', null])) {
            $candidature->update(['statut' => 'en_cours']);
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'Déjà lue']);
    }

    /**
     * Met à jour le profil du recruteur et les informations de l'entreprise
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $recruteur = $user->recruteur;

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'nom_entreprise' => 'required|string|max:255',
            'secteur_activite' => 'nullable|string|max:255',
            'taille_entreprise' => 'required|in:startup,pme,grande_entreprise,multinationale',
            'site_web' => 'nullable|url|max:255',
            'description_ent' => 'nullable|string',
            'logo_entreprise' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Mise à jour de l'utilisateur
        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        // Préparation des données du recruteur
        $recruteurData = [
            'nom_entreprise' => $validated['nom_entreprise'],
            'secteur_activite' => $validated['secteur_activite'],
            'taille_entreprise' => $validated['taille_entreprise'],
            'site_web' => $validated['site_web'],
            'description_ent' => $validated['description_ent'],
        ];

        // Gestion du logo
        if ($request->hasFile('logo_entreprise')) {
            // Optionnel : supprimer l'ancien logo s'il existe
            if ($recruteur->logo_entreprise && \Storage::disk('public')->exists($recruteur->logo_entreprise)) {
                \Storage::disk('public')->delete($recruteur->logo_entreprise);
            }

            $logoPath = $request->file('logo_entreprise')->store('logos', 'public');
            $recruteurData['logo_entreprise'] = $logoPath;
        }

        // Mise à jour du recruteur
        $recruteur->update($recruteurData);

        return back()->with('success', 'Profil mis à jour avec succès !');
    }

    /**
     * Affiche le réseau des recruteurs pour la communication entre pairs
     */
    public function network()
    {
        $user_actuel = Auth::user();
        $recruteur_actuel = $user_actuel->recruteur;

        // Liste des autres recruteurs vérifiés
        $autres_recruteurs = \App\Models\Recruteur::with('user')
            ->where('id', '!=', $recruteur_actuel->id)
            ->where('is_verified', true)
            ->get();

        // Conversations entre recruteurs (sans lien avec une candidature spécifique)
        $messages = \App\Models\Message::where(function ($q) use ($user_actuel) {
            $q->where('expediteur_id', $user_actuel->id)
                ->orWhere('destinataire_id', $user_actuel->id);
        })
            ->whereNull('candidature_id')
            ->with(['expediteur.recruteur', 'destinataire.recruteur'])
            ->orderBy('created_at', 'asc')
            ->get();

        // Grouper par interlocuteur
        $conversations = $messages->groupBy(function ($msg) use ($user_actuel) {
            return $msg->expediteur_id == $user_actuel->id ? $msg->destinataire_id : $msg->expediteur_id;
        });

        return view('recruteur.network', compact('autres_recruteurs', 'conversations'));
    }
}
