<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string  $role
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $role)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if (Auth::user()->role !== $role) {
            // Rediriger selon le rôle actuel de l'utilisateur
            $userRole = Auth::user()->role;
            
            switch ($userRole) {
                case 'candidat':
                    return redirect()->route('candidat.dashboard');
                case 'recruteur':
                    return redirect()->route('recruteur.dashboard');
                case 'administrateur':
                    return redirect()->route('admin.dashboard');
                default:
                    return redirect('/');
            }
        }

        return $next($request);
    }
}
