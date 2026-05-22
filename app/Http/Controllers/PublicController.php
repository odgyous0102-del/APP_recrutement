<?php

namespace App\Http\Controllers;

use App\Models\Offre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PublicController extends Controller
{
    /**
     * Affiche la liste publique des offres d'emploi.
     */
    public function offres(Request $request)
    {
        $query = Offre::with('recruteur')->withCount('candidatures')->where('statut', 'publie');

        // Recherche par mot-clé
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('titre', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('lieu', 'like', "%{$search}%")
                  ->orWhereHas('recruteur', function ($qRecruteur) use ($search) {
                      $qRecruteur->where('nom_entreprise', 'like', "%{$search}%");
                  });
            });
        }

        // Filtres
        if ($request->filled('type_contrat')) {
            $query->where('type_contrat', $request->input('type_contrat'));
        }

        if ($request->filled('teletravail')) {
            $query->where('teletravail', $request->input('teletravail'));
        }

        $offres = $query->orderBy('created_at', 'desc')->paginate(12);

        // Si l'utilisateur est connecté en tant que candidat, on récupère ses candidatures
        $appliedOfferIds = [];
        if (Auth::check() && Auth::user()->isCandidat()) {
            $appliedOfferIds = Auth::user()->candidat->candidatures()->pluck('offre_id')->toArray();
        }

        return view('public.offres', compact('offres', 'appliedOfferIds'));
    }

    /**
     * Affiche la liste publique des entreprises (recruteurs).
     */
    public function entreprises(Request $request)
    {
        $query = \App\Models\Recruteur::withCount(['offres' => function ($query) {
                $query->where('statut', 'publie');
            }]);

        // Recherche par mot-clé
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('nom_entreprise', 'like', "%{$search}%")
                  ->orWhere('secteur_activite', 'like', "%{$search}%")
                  ->orWhere('description_ent', 'like', "%{$search}%");
            });
        }

        $entreprises = $query->orderBy('nom_entreprise', 'asc')->paginate(12);

        return view('public.entreprises', compact('entreprises'));
    }

    /**
     * Affiche la page de conseils carrière.
     */
    public function conseils()
    {
        return view('public.conseils');
    }

    /**
     * Affiche un article générique pour la démonstration.
     */
    public function article($slug = null)
    {
        return view('public.article', compact('slug'));
    }
}
