<?php

namespace App\Http\Controllers;

use App\Models\Entretien;
use App\Models\Candidature;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EntretienController extends Controller
{
    /**
     * Planifie un nouvel entretien.
     */
    public function store(Request $request)
    {
        // Validation plus souple pour la date (permet aujourd'hui)
        $request->validate([
            'candidature_id' => 'required|exists:candidatures,id',
            'date_entretien' => 'required|date',
            'heure_entretien' => 'required',
            'type' => 'required|in:telephonique,visio,physique',
            'lien_visio' => 'nullable|required_if:type,visio',
            'duree_minutes' => 'required|integer|min:5|max:480',
            'notes' => 'nullable|string',
        ]);

        $candidature = Candidature::with(['offre', 'candidat.user'])->findOrFail($request->candidature_id);
        
        // Sécurité supplémentaire pour l'accès recruteur
        $user = Auth::user();
        if (!$user->recruteur || $candidature->offre->recruteur_id !== $user->recruteur->id) {
            return back()->with('error', 'Action non autorisée ou profil recruteur manquant.');
        }

        // Fusionner date et heure pour créer un objet Carbon
        try {
            $dateTimeStr = $request->date_entretien . ' ' . $request->heure_entretien;
            $dateTime = \Carbon\Carbon::parse($dateTimeStr);
            
            if ($dateTime->isPast()) {
                return back()->withInput()->with('error', 'La date et l\'heure de l\'entretien ne peuvent pas être dans le passé.');
            }
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Format de date ou d\'heure invalide.');
        }

        $entretien = Entretien::create([
            'candidature_id' => $candidature->id,
            'planifie_par' => $user->id,
            'date_entretien' => $dateTime,
            'duree_minutes' => $request->duree_minutes,
            'type' => $request->type,
            'lien_visio' => $request->lien_visio,
            'statut' => 'planifie',
            'compte_rendu' => $request->notes,
        ]);

        // Mapper le type d'entretien à une étape de recrutement valide
        $etape = match($request->type) {
            'telephonique' => 'entretien_telephonique',
            'visio' => 'entretien_technique',
            'physique' => 'entretien_final',
            default => 'entretien_technique'
        };

        $candidature->update(['etape_recrutement' => $etape]);

        // Envoyer une notification au candidat
        Notification::create([
            'user_id' => $candidature->candidat->user_id,
            'type' => 'entretien_planifie',
            'titre' => 'Nouvel entretien planifié',
            'message' => "Un entretien de type {$request->type} a été planifié pour le poste : " . $candidature->offre->titre . ". Date : " . $dateTime->format('d/m/Y à H:i'),
            'data' => [
                'entretien_id' => $entretien->id,
                'candidature_id' => $candidature->id,
                'type_entretien' => $request->type,
                'date' => $dateTime->toDateTimeString(),
            ],
        ]);

        return back()->with('success', 'L\'entretien a été planifié avec succès pour ' . $candidature->candidat->user->name);
    }
    /**
     * Confirme un entretien (action du candidat).
     */
    public function confirmer(Entretien $entretien)
    {
        // Vérifier que c'est bien l'entretien du candidat connecté
        $candidat = Auth::user()->candidat;
        if (!$candidat || $entretien->candidature->candidat_id !== $candidat->id) {
            abort(403, 'Action non autorisée.');
        }

        if ($entretien->statut !== 'planifie') {
            return back()->with('error', 'Cet entretien ne peut plus être confirmé.');
        }

        $entretien->update(['statut' => 'confirme']);

        return back()->with('success', 'Entretien confirmé avec succès !');
    }
    /**
     * Annule un entretien (action du candidat ou recruteur).
     */
    public function annuler(Entretien $entretien)
    {
        $user = Auth::user();
        
        // Vérifier les droits (soit le candidat, soit le recruteur propriétaire)
        $isCandidat = $user->candidat && $entretien->candidature->candidat_id === $user->candidat->id;
        $isRecruteur = $user->recruteur && $entretien->candidature->offre->recruteur_id === $user->recruteur->id;

        if (!$isCandidat && !$isRecruteur) {
            abort(403, 'Action non autorisée.');
        }

        if ($entretien->statut === 'termine' || $entretien->statut === 'annule') {
            return back()->with('error', 'Cet entretien ne peut plus être annulé.');
        }

        $entretien->update(['statut' => 'annule']);

        return back()->with('success', 'Entretien annulé.');
    }
}
