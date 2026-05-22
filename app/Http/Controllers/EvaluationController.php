<?php

namespace App\Http\Controllers;

use App\Models\Evaluation;
use App\Models\Entretien;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EvaluationController extends Controller
{
    /**
     * Enregistre une nouvelle évaluation pour un entretien.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'entretien_id' => 'required|exists:entretiens,id',
            'competences_techniques' => 'required|integer|min:0|max:5',
            'communication' => 'required|integer|min:0|max:5',
            'motivation' => 'required|integer|min:0|max:5',
            'culture_fit' => 'required|integer|min:0|max:5',
            'recommandation' => 'required|in:fortement_recommande,recommande,neutre,pas_recommande,fortement_pas_recommande',
            'commentaires' => 'nullable|string',
        ]);

        // Calcul de la note globale (moyenne simple)
        $noteGlobale = ($validated['competences_techniques'] + 
                        $validated['communication'] + 
                        $validated['motivation'] + 
                        $validated['culture_fit']) / 4;

        $evaluation = Evaluation::updateOrCreate(
            ['entretien_id' => $validated['entretien_id']],
            [
                'evaluateur_id' => Auth::id(),
                'competences_techniques' => $validated['competences_techniques'],
                'communication' => $validated['communication'],
                'motivation' => $validated['motivation'],
                'culture_fit' => $validated['culture_fit'],
                'note_globale' => $noteGlobale,
                'recommandation' => $validated['recommandation'],
                'commentaires' => $validated['commentaires'],
            ]
        );

        // Mettre à jour le statut de l'entretien si nécessaire
        $entretien = Entretien::with('candidature.offre')->find($validated['entretien_id']);
        $entretien->update(['statut' => 'termine']);

        // Envoyer une notification au candidat
        Notification::create([
            'user_id' => $entretien->candidature->candidat->user_id,
            'type' => 'entretien_evalue',
            'titre' => 'Entretien évalué',
            'message' => "Votre entretien pour le poste : " . $entretien->candidature->offre->titre . " a été évalué par le recruteur.",
            'data' => [
                'entretien_id' => $entretien->id,
                'candidature_id' => $entretien->candidature_id,
                'note_globale' => $noteGlobale,
            ],
        ]);

        return back()->with('success', 'Évaluation enregistrée avec succès.');
    }
}
