<?php

namespace App\Http\Controllers;

use App\Models\Offre;
use App\Models\Candidat;
use App\Models\Candidature;
use App\Models\Message;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CandidatDashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $candidat = $user->candidat;

        if (!$candidat) {
            return redirect()->route('welcome')->with('error', 'Profil candidat non trouvé.');
        }

        // Load relations
        $candidat->load(['experiences', 'formations']);

        // Fetch applications with job offers
        $candidatures = Candidature::where('candidat_id', $candidat->id)
            ->with(['offre.recruteur.user', 'entretiens'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Fetch interviews (entretiens) for the candidate
        $entretiens = \App\Models\Entretien::whereIn('candidature_id', $candidatures->pluck('id'))
            ->with(['candidature.offre.recruteur.user'])
            ->orderBy('date_entretien', 'asc')
            ->get();

        // Fetch messages (all sent or received), ordered chronologically
        $messages = Message::where('expediteur_id', $user->id)
            ->orWhere('destinataire_id', $user->id)
            ->with(['expediteur', 'destinataire'])
            ->orderBy('created_at', 'asc')
            ->get();

        // Build conversations: group by the OTHER participant's ID
        $conversations = $messages->groupBy(function ($msg) use ($user) {
            // Si le candidat est l'expéditeur → l'autre est le destinataire
            // Si le candidat est le destinataire → l'autre est l'expéditeur
            return $msg->expediteur_id === $user->id
                ? $msg->destinataire_id
                : $msg->expediteur_id;
        });

        // Build query for job offers (active ones) with filters
        $query = Offre::where('statut', 'publie')->with('recruteur')->withCount('candidatures');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('titre', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhereHas('recruteur', function ($q2) use ($search) {
                        $q2->where('nom_entreprise', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('lieu')) {
            $query->where('lieu', 'like', '%' . $request->input('lieu') . '%');
        }

        if ($request->filled('type_contrat')) {
            $query->where('type_contrat', $request->input('type_contrat'));
        }

        if ($request->filled('salaire_min')) {
            $query->where('salaire_min', '>=', $request->input('salaire_min'));
        }

        if ($request->filled('teletravail')) {
            $query->where('teletravail', $request->input('teletravail'));
        }

        $offres = $query->orderBy('created_at', 'desc')->get();

        // Fetch favorites
        $favorisIds = \App\Models\OffreFavori::where('user_id', $user->id)
            ->pluck('offre_id')
            ->toArray();

        $offresFavorites = Offre::whereIn('id', $favorisIds)
            ->with('recruteur')
            ->get();

        // Fetch IDs of offers already applied for
        $appliedOfferIds = $candidatures->pluck('offre_id')->toArray();

        return view('dashboard-candidat', compact(
            'candidat', 'candidatures', 'messages', 'conversations',
            'offres', 'entretiens', 'favorisIds', 'offresFavorites', 'appliedOfferIds'
        ));
    }

    public function postuler(Request $request, Offre $offre)
    {
        $user = Auth::user();
        $candidat = $user->candidat;

        // Check if already applied
        $existing = Candidature::where('candidat_id', $candidat->id)
            ->where('offre_id', $offre->id)
            ->first();

        if ($existing) {
            return redirect()->back()->with('error', 'Vous avez déjà postulé à cette offre.');
        }

        // Validation
        $request->validate([
            'cv' => 'required|file|mimes:pdf,doc,docx|max:2048',
            'lettre_motivation' => 'required|string|min:20',
            'pretention_salariale' => 'nullable|numeric|min:0',
            'disponibilite' => 'nullable|string',
        ]);

        // Handle CV Upload
        $cvPath = null;
        if ($request->hasFile('cv')) {
            $cvPath = $request->file('cv')->store('cvs', 'public');
        }

        // Calcul du score de matching (bonus)
        $score = 0;
        $candidatSkills = is_array($candidat->competences) ? $candidat->competences : [];
        $jobSkills = is_array($offre->competences_req) ? $offre->competences_req : [];

        if (count($jobSkills) > 0) {
            $matches = array_intersect(array_map('strtolower', $candidatSkills), array_map('strtolower', $jobSkills));
            $score = round((count($matches) / count($jobSkills)) * 100);
        }

        Candidature::create([
            'candidat_id' => $candidat->id,
            'offre_id' => $offre->id,
            'cv_path' => $cvPath,
            'lettre_motivation' => $request->lettre_motivation,
            'disponibilite' => $request->disponibilite,
            'pretention_salariale' => $request->pretention_salariale,
            'statut' => 'en_attente',
            'etape_recrutement' => 'candidature',
            'score_matching' => $score,
            'date_soumission' => now(),
        ]);

        // Envoyer une notification au recruteur
        Notification::create([
            'user_id' => $offre->recruteur->user_id,
            'type' => 'nouvelle_candidature',
            'titre' => 'Nouvelle candidature reçue',
            'message' => $user->name . " a postulé pour le poste : " . $offre->titre,
            'data' => [
                'offre_id' => $offre->id,
                'candidat_id' => $candidat->id,
                'user_id' => $user->id,
            ],
        ]);

        return redirect()->back()->with('success', 'Votre candidature a été envoyée avec succès !');
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $candidat = $user->candidat;

        $validated = $request->validate([
            'titre_professionnel' => 'nullable|string|max:255',
            'niveau_experience' => 'nullable|in:debutant,junior,confirme,senior,expert',
            'disponibilite' => 'nullable|in:immediate,1_mois,3_mois,6_mois,plus_6_mois',
            'portfolio_url' => 'nullable|url',
            'linkedin_url' => 'nullable|url',
            'pretention_salariale' => 'nullable|numeric|min:0',
            'is_open_to_relocation' => 'nullable|boolean',
            'competences' => 'nullable|string',
            'langues' => 'nullable|string',
        ]);

        // Fix for checkbox
        $validated['is_open_to_relocation'] = $request->has('is_open_to_relocation');

        // Conversion des chaînes en tableaux
        if ($request->has('competences')) {
            $validated['competences'] = array_filter(array_map('trim', explode(',', $request->competences)));
        }

        if ($request->has('langues')) {
            $validated['langues'] = array_filter(array_map('trim', explode(',', $request->langues)));
        }

        $candidat->update($validated);

        return redirect()->back()->with('success', 'Profil mis à jour avec succès !');
    }

    public function sendMessage(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'destinataire_id' => 'required|integer|exists:users,id',
            'candidature_id'  => 'nullable|integer|exists:candidatures,id',
            'contenu'         => 'required|string|min:1|max:5000',
        ]);

        // L'expéditeur est toujours l'utilisateur connecté
        // Le destinataire est fourni par le formulaire
        $message = Message::create([
            'expediteur_id'  => $user->id,
            'destinataire_id'=> (int) $validated['destinataire_id'],
            'candidature_id' => isset($validated['candidature_id']) ? (int) $validated['candidature_id'] : null,
            'contenu'        => trim($validated['contenu']),
            'type'           => 'message',
        ]);

        // Charger les relations pour la réponse JSON
        $message->load(['expediteur', 'destinataire']);

        // Toujours retourner JSON pour les requêtes AJAX/JSON
        if ($request->expectsJson() || $request->ajax() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json([
                'success' => true,
                'message' => $message,
            ]);
        }

        return redirect()->back()->with('success', 'Message envoyé avec succès !');
    }

    public function toggleFavori(Request $request, Offre $offre)
    {
        $user = Auth::user();

        $favori = \App\Models\OffreFavori::where('user_id', $user->id)
            ->where('offre_id', $offre->id)
            ->first();

        if ($favori) {
            $favori->delete();
            $status = 'removed';
        } else {
            \App\Models\OffreFavori::create([
                'user_id' => $user->id,
                'offre_id' => $offre->id,
            ]);
            $status = 'added';
        }

        if ($request->ajax()) {
            return response()->json(['status' => $status]);
        }

        return redirect()->back()->with('success', $status == 'added' ? 'Offre ajoutée aux favoris.' : 'Offre retirée des favoris.');
    }

    public function addExperience(Request $request)
    {
        $request->validate([
            'poste' => 'required|string|max:255',
            'entreprise' => 'required|string|max:255',
            'localisation' => 'nullable|string|max:255',
            'date_debut' => 'required|date',
            'date_fin' => 'nullable|date|after_or_equal:date_debut',
            'en_cours' => 'nullable|boolean',
            'description' => 'nullable|string',
        ]);

        Auth::user()->candidat->experiences()->create($request->all());

        return redirect()->back()->with('success', 'Expérience ajoutée !');
    }

    public function deleteExperience(\App\Models\Experience $experience)
    {
        if ($experience->candidat_id !== Auth::user()->candidat->id) {
            abort(403);
        }
        $experience->delete();
        return redirect()->back()->with('success', 'Expérience supprimée !');
    }

    public function addFormation(Request $request)
    {
        $request->validate([
            'diplome' => 'required|string|max:255',
            'etablissement' => 'required|string|max:255',
            'domaine' => 'nullable|string|max:255',
            'niveau' => 'nullable|in:licence,master,doctorat,autre',
            'date_debut' => 'required|integer',
            'date_fin' => 'nullable|integer|gte:date_debut',
            'mention' => 'nullable|string|max:255',
        ]);

        Auth::user()->candidat->formations()->create($request->all());

        return redirect()->back()->with('success', 'Formation ajoutée !');
    }

    public function deleteFormation(\App\Models\Formation $formation)
    {
        if ($formation->candidat_id !== Auth::user()->candidat->id) {
            abort(403);
        }
        $formation->delete();
        return redirect()->back()->with('success', 'Formation supprimée !');
    }
}
