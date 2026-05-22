<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RecruteurMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Vérifier si l'utilisateur est authentifié
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Vérifier si l'utilisateur a le rôle 'recruteur'
        if (Auth::user()->role !== 'recruteur') {
            switch (Auth::user()->role) {
                case 'candidat':
                    return redirect()->route('candidat.dashboard');
                case 'administrateur':
                    return redirect()->route('admin.dashboard');
                default:
                    return redirect()->route('welcome');
            }
        }

        $recruteur = Auth::user()->recruteur;

        // Vérifier si le recruteur a un profil recruteur
        if (!$recruteur) {
            return redirect()->route('welcome')->with('error', 'Votre profil recruteur n\'est pas complet.');
        }

        // --- NOUVELLE RÈGLE DE SÉCURITÉ ---
        // Si le recruteur n'est pas vérifié, on bloque tout SAUF la messagerie et la mise à jour du profil
        if (!$recruteur->is_verified) {
            $allowedRoutes = [
                'recruteur.dashboard', // On laisse voir le dashboard pour afficher le message d'attente
                'recruteur.messages.store',
                'recruteur.network',
                'recruteur.profile.update',
                'logout'
            ];

            if (!in_array($request->route()->getName(), $allowedRoutes)) {
                return redirect()->route('recruteur.dashboard')->with('error', 'Votre compte est en attente de validation par l\'administrateur. Vous ne pouvez pas encore effectuer cette action.');
            }
        }

        return $next($request);
    }
}
