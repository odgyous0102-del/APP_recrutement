<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Candidat;
use App\Models\Recruteur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login-new');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            
            if (!$user->is_active) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Votre compte a été désactivé par l\'administrateur.',
                ])->onlyInput('email');
            }

            $request->session()->regenerate();
            
            // Redirection selon le rôle
            switch ($user->role) {
                case 'candidat':
                    return redirect()->route('candidat.dashboard');
                case 'recruteur':
                    return redirect()->route('recruteur.dashboard');
                case 'administrateur':
                    return redirect()->route('admin.dashboard');
                default:
                    return redirect('/dashboard');
            }
        }

        return back()->withErrors([
            'email' => 'Les identifiants fournis ne correspondent pas à nos enregistrements.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    public function showRegisterForm()
    {
        return view('auth.register-choice');
    }

    public function showCandidatRegisterForm()
    {
        return view('auth.register-candidat');
    }

    public function showRecruteurRegisterForm()
    {
        return view('auth.register-recruteur');
    }

    public function registerCandidat(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'telephone' => 'nullable|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'ville' => 'nullable|string|max:255',
            'pays' => 'nullable|string|max:255',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'titre_professionnel' => 'nullable|string|max:255',
            'niveau_experience' => 'nullable|in:debutant,junior,confirme,senior,expert',
            'disponibilite' => 'nullable|in:immediate,1_mois,3_mois,6_mois,plus_6_mois',
            'pretention_salariale' => 'nullable|numeric|min:0',
            'linkedin_url' => 'nullable|url',
            'is_open_to_relocation' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $profilePicturePath = null;
            if ($request->hasFile('profile_picture')) {
                $profilePicturePath = $request->file('profile_picture')->store('profiles', 'public');
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'telephone' => $request->telephone,
                'password' => Hash::make($request->password),
                'role' => 'candidat',
                'is_active' => true,
                'ville' => $request->ville,
                'pays' => $request->pays,
                'profile_picture' => $profilePicturePath,
            ]);

            // Préparer les données du candidat
            $candidatData = [
                'user_id' => $user->id,
                'titre_professionnel' => $request->titre_professionnel ?: null,
                'niveau_experience' => $request->niveau_experience ?: null,
                'disponibilite' => $request->disponibilite ?: null,
                'pretention_salariale' => $request->pretention_salariale ? (float) $request->pretention_salariale : null,
                'linkedin_url' => $request->linkedin_url ?: null,
                'is_open_to_relocation' => $request->has('is_open_to_relocation'),
            ];

            // Créer le profil candidat
            Candidat::create($candidatData);

            Auth::login($user);

            return redirect()->route('candidat.dashboard')->with('success', 'Votre compte candidat a été créé avec succès!');
        } catch (\Exception $e) {
            \Log::error('Error during candidate registration:', ['error' => $e->getMessage()]);
            return back()->with('error', 'Une erreur est survenue lors de la création de votre compte.')->withInput();
        }
    }

    public function registerRecruteur(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'telephone' => 'nullable|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'ville' => 'nullable|string|max:255',
            'pays' => 'nullable|string|max:255',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'nom_entreprise' => 'required|string|max:255',
            'secteur_activite' => 'nullable|string|max:255',
            'taille_entreprise' => 'required|in:startup,pme,grande_entreprise,multinationale',
            'site_web' => 'nullable|url',
            'description_ent' => 'nullable|string',
            'contact_rh_email' => 'nullable|email',
            'numero_rc' => 'nullable|string|max:255',
            'logo_entreprise' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $profilePicturePath = null;
            if ($request->hasFile('profile_picture')) {
                $profilePicturePath = $request->file('profile_picture')->store('profiles', 'public');
            }

            $logoPath = null;
            if ($request->hasFile('logo_entreprise')) {
                $logoPath = $request->file('logo_entreprise')->store('logos', 'public');
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'telephone' => $request->telephone,
                'password' => Hash::make($request->password),
                'role' => 'recruteur',
                'is_active' => true,
                'ville' => $request->ville,
                'pays' => $request->pays,
                'profile_picture' => $profilePicturePath,
            ]);

            // Créer le profil recruteur
            Recruteur::create([
                'user_id' => $user->id,
                'nom_entreprise' => $request->nom_entreprise,
                'secteur_activite' => $request->secteur_activite,
                'taille_entreprise' => $request->taille_entreprise,
                'site_web' => $request->site_web,
                'description_ent' => $request->description_ent,
                'contact_rh_email' => $request->contact_rh_email,
                'numero_rc' => $request->numero_rc,
                'logo_entreprise' => $logoPath,
                'is_verified' => false,
                'statut_verification' => 'en_attente',
                'quota_offres' => 5,
            ]);

            Auth::login($user);

            return redirect()->route('recruteur.dashboard')->with('success', 'Votre compte recruteur a été créé avec succès!');
        } catch (\Exception $e) {
            \Log::error('Error during recruiter registration:', ['error' => $e->getMessage()]);
            return back()->with('error', 'Une erreur est survenue lors de la création de votre compte.')->withInput();
        }
    }
}
