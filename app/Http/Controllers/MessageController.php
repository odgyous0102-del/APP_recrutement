<?php
namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Candidature;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    /**
     * Envoie un message au candidat lié à une candidature.
     */
    public function store(Request $request)
    {
        try {
            $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
                'candidature_id'  => 'nullable',
                'destinataire_id' => 'nullable',
                'contenu'         => 'required|string|max:5000',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Données manquantes ou invalides : ' . implode(' ', $validator->errors()->all()) . ' (Champs reçus : ' . implode(', ', array_keys($request->all())) . ')'
                ], 422);
            }

            $candidatureId  = $request->input('candidature_id');
            $destinataireId = $request->input('destinataire_id');
            $candidature    = null;

            if ($candidatureId && is_numeric($candidatureId)) {
                $candidature = Candidature::with(['offre.recruteur', 'candidat.user'])->find($candidatureId);
                
                if ($candidature) {
                    $destinataireId = $candidature->candidat->user_id;

                    // Vérification de sécurité
                    $userRecruteur = Auth::user()->recruteur;
                    if (!$userRecruteur || $candidature->offre->recruteur_id != $userRecruteur->id) {
                        return response()->json([
                            'success' => false, 
                            'message' => 'Vous n\'êtes pas autorisé à envoyer un message pour cette candidature.'
                        ], 403);
                    }
                }
            }

            if (!$destinataireId || !is_numeric($destinataireId)) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Destinataire non identifié. Veuillez sélectionner une conversation.'
                ], 422);
            }

            $message = Message::create([
                'expediteur_id'   => Auth::id(),
                'destinataire_id' => (int)$destinataireId,
                'candidature_id'  => $candidatureId && is_numeric($candidatureId) ? (int)$candidatureId : null,
                'contenu'         => $request->input('contenu'),
                'type'            => 'message',
            ]);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $message->load(['candidature.candidat.user', 'candidature.offre', 'expediteur', 'destinataire'])
                ]);
            }

            return back()->with('success', 'Votre message a été envoyé avec succès.');
        } catch (\Exception $e) {
            return response()->json([
                'success' => false, 
                'message' => 'Erreur système : ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Marque les messages d'une conversation comme lus.
     */
    public function markAsRead(Request $request, $senderId)
    {
        $userId = Auth::id();
        
        Message::where('destinataire_id', $userId)
            ->where('expediteur_id', $senderId)
            ->whereNull('lu_at')
            ->update(['lu_at' => now()]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return back();
    }
}
