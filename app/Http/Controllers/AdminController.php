<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Recruteur;
use App\Models\Candidat;
use App\Models\Offre;
use App\Models\Candidature;
use App\Models\AuditLog;
use App\Models\Message;
use App\Models\Notification;
use App\Models\VerificationRecruteur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminController extends Controller
{
    /**
     * Affiche le tableau de bord administrateur avec statistiques globales
     */
    public function index()
    {
        // Statistiques générales
        $stats = [
            'total_users' => User::count(),
            'total_recruteurs' => Recruteur::count(),
            'total_candidats' => Candidat::count(),
            'total_offres' => Offre::count(),
            'total_candidatures' => Candidature::count(),
            'offres_actives' => Offre::where('statut', 'publie')->count(),
            'candidatures_en_attente' => Candidature::where('statut', 'en_attente')->count(),
            'recruteurs_verifies' => Recruteur::where('is_verified', true)->count(),
            'recruteurs_en_attente' => Recruteur::where('statut_verification', 'en_attente')->count(),
        ];

        // Messages non lus
        $messagesNonLus = Message::where('destinataire_id', Auth::id())
            ->whereNull('lu_at')
            ->count();

        // Logs récents (lecture seule)
        $recentLogs = AuditLog::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get();

        // Utilisateurs récents
        $recentUsers = User::orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Offres récentes
        $recentOffres = Offre::with('recruteur')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Historique des vérifications recruteurs
        $historiqueVerifications = VerificationRecruteur::with(['recruteur.user', 'admin.user'])
            ->orderBy('date_decision', 'desc')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'messagesNonLus',
            'recentLogs',
            'recentUsers',
            'recentOffres',
            'historiqueVerifications'
        ));
    }

    /**
     * Affiche la liste des recruteurs en attente de vérification
     */
    public function verifyRecruteur()
    {
        $recruteursEnAttente = Recruteur::with('user')
            ->where('statut_verification', 'en_attente')
            ->orderBy('created_at', 'desc')
            ->get();

        // Récupérer l'historique depuis la table de vérification
        $historiqueVerifications = VerificationRecruteur::with(['recruteur.user', 'admin.user'])
            ->orderBy('date_decision', 'desc')
            ->limit(20)
            ->get();

        return view('admin.verify-recruteurs', [
            'recruteursEnAttente' => $recruteursEnAttente,
            'historiqueVerifications' => $historiqueVerifications
        ]);
    }

    /**
     * Approuve ou rejette un recruteur
     */
    public function approveRecruteur(Request $request, $recruteurId)
    {
        $request->validate([
            'action' => 'required|in:approve,reject',
            'commentaire' => 'nullable|string|max:1000',
        ]);

        $recruteur = Recruteur::findOrFail($recruteurId);

        if ($request->action === 'approve') {
            $recruteur->update([
                'is_verified' => true,
                'statut_verification' => 'approuve',
            ]);
        } else {
            $recruteur->update([
                'is_verified' => false,
                'statut_verification' => 'rejete',
            ]);
        }

        // Enregistrer dans la table de vérification
        VerificationRecruteur::create([
            'recruteur_id' => $recruteur->id,
            'admin_id' => Auth::user()->administrateur->id ?? null,
            'statut' => $request->action === 'approve' ? 'approuve' : 'rejete',
            'commentaire' => $request->commentaire,
            'date_demande' => $recruteur->created_at,
            'date_decision' => now(),
        ]);

        // Logger l'action
        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => $request->action === 'approve' ? 'approve_recruteur' : 'reject_recruteur',
            'model_type' => 'Recruteur',
            'model_id' => $recruteur->id,
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', 'Recruteur ' . ($request->action === 'approve' ? 'approuvé' : 'rejeté') . ' avec succès!');
    }

    /**
     * Gestion des utilisateurs - liste complète
     */
    public function manageUsers(Request $request)
    {
        $search = $request->get('search');
        $role = $request->get('role');
        $status = $request->get('status');

        $users = User::with(['candidat', 'recruteur', 'administrateur'])
            ->when($search, function($query) use ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when($role, function($query) use ($role) {
                $query->where('role', $role);
            })
            ->when($status !== null && $status !== 'all', function($query) use ($status) {
                $query->where('is_active', $status === 'active');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $statsParRole = [
            'candidats' => Candidat::count(),
            'recruteurs' => Recruteur::count(),
            'administrateurs' => User::where('role', 'administrateur')->count(),
        ];

        return view('admin.users', compact('users', 'statsParRole'));
    }

    /**
     * Active ou désactive un utilisateur
     */
    public function toggleUserStatus(Request $request, $userId)
    {
        $user = User::findOrFail($userId);
        $user->update(['is_active' => !$user->is_active]);

        // Logger l'action
        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => $user->is_active ? 'activate_user' : 'deactivate_user',
            'model_type' => 'User',
            'model_id' => $user->id,
            'nouvelle_val' => ['is_active' => $user->is_active],
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', 'Statut de l\'utilisateur mis à jour!');
    }

    /**
     * Affiche les logs d'audit (lecture seule)
     */
    public function viewLogs(Request $request)
    {
        $search = $request->get('search');
        $action = $request->get('action');
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');

        $logs = AuditLog::with('user')
            ->when($search, function($query) use ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('action', 'like', "%{$search}%")
                        ->orWhereHas('user', function($uq) use ($search) {
                            $uq->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                        });
                });
            })
            ->when($action, function($query) use ($action) {
                $query->where('action', $action);
            })
            ->when($dateFrom, function($query) use ($dateFrom) {
                $query->whereDate('created_at', '>=', $dateFrom);
            })
            ->when($dateTo, function($query) use ($dateTo) {
                $query->whereDate('created_at', '<=', $dateTo);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(50);

        // Actions uniques pour le filtre
        $actions = AuditLog::distinct()->pluck('action');

        return view('admin.logs', compact('logs', 'actions'));
    }

    /**
     * Affiche les messages organisés par conversations (inspiré du dashboard candidat/recruteur)
     */
    public function messages(Request $request)
    {
        $user = Auth::user();
        
        // Récupérer tous les messages liés à l'admin
        $messages = Message::where('expediteur_id', $user->id)
            ->orWhere('destinataire_id', $user->id)
            ->with(['expediteur.recruteur', 'expediteur.candidat', 'destinataire.recruteur', 'destinataire.candidat'])
            ->orderBy('created_at', 'asc')
            ->get();

        // Grouper par interlocuteur
        $conversations = $messages->groupBy(function($msg) use ($user) {
            return $msg->expediteur_id == $user->id ? $msg->destinataire_id : $msg->expediteur_id;
        });

        // Liste des destinataires potentiels (Recruteurs et Candidats)
        $recruteurs = User::where('role', 'recruteur')
            ->where('is_active', true)
            ->with('recruteur')
            ->orderBy('name')
            ->get();

        $candidats = User::where('role', 'candidat')
            ->where('is_active', true)
            ->with('candidat')
            ->orderBy('name')
            ->get();

        return view('admin.messages', compact('conversations', 'recruteurs', 'candidats'));
    }

    /**
     * Envoie un message à un recruteur
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'destinataire_id' => 'required|exists:users,id',
            'contenu' => 'required|string|max:5000',
        ]);

        // Vérifier que le destinataire n'est pas un admin (optionnel)
        $destinataire = User::findOrFail($request->destinataire_id);
        if ($destinataire->role === 'administrateur' && $destinataire->id !== Auth::id()) {
            // On peut autoriser l'admin-to-admin ou pas, ici on reste sur la communication externe
        }

        Message::create([
            'expediteur_id' => Auth::id(),
            'destinataire_id' => $request->destinataire_id,
            'contenu' => $request->contenu,
            'type' => 'message',
        ]);

        // Logger l'action
        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'send_message_to_recruteur',
            'model_type' => 'Message',
            'model_id' => null,
            'nouvelle_val' => ['destinataire_id' => $request->destinataire_id],
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', 'Message envoyé avec succès au recruteur!');
    }

    /**
     * Marque un message comme lu
     */
    public function markMessageAsRead($messageId)
    {
        $message = Message::where('destinataire_id', Auth::id())
            ->findOrFail($messageId);

        $message->update(['lu_at' => now()]);

        return back();
    }

    /**
     * Affiche les statistiques détaillées de la plateforme
     */
    public function statistics()
    {
        $candidaturesParMois = Candidature::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('COUNT(*) as count')
        )
            ->whereYear('created_at', Carbon::now()->year)
            ->groupBy('month')
            ->get();

        $offresParStatut = Offre::select('statut', DB::raw('COUNT(*) as count'))
            ->groupBy('statut')
            ->get();

        $candidaturesParStatut = Candidature::select('statut', DB::raw('COUNT(*) as count'))
            ->groupBy('statut')
            ->get();

        $topEntreprises = Recruteur::withCount('offres')
            ->orderBy('offres_count', 'desc')
            ->limit(10)
            ->get();

        return view('admin.statistics', compact(
            'candidaturesParMois',
            'offresParStatut',
            'candidaturesParStatut',
            'topEntreprises'
        ));
    }

    /**
     * Affiche l'interface d'envoi de notifications système
     */
    public function notifications()
    {
        $users = User::where('role', '!=', 'administrateur')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $recentNotifications = Notification::with('user')
            ->where('type', 'system_broadcast')
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();

        return view('admin.notifications', compact('users', 'recentNotifications'));
    }

    /**
     * Envoie une notification système à un utilisateur ou à tous
     */
    public function sendSystemNotification(Request $request)
    {
        $request->validate([
            'user_id' => 'required', // 'all' ou ID utilisateur
            'titre' => 'required|string|max:255',
            'message' => 'required|string|max:5000',
        ]);

        if ($request->user_id === 'all') {
            $users = User::where('is_active', true)->get();
            foreach ($users as $user) {
                Notification::create([
                    'user_id' => $user->id,
                    'type' => 'system_broadcast',
                    'titre' => $request->titre,
                    'message' => $request->message,
                ]);
            }
        } else {
            Notification::create([
                'user_id' => $request->user_id,
                'type' => 'system_broadcast',
                'titre' => $request->titre,
                'message' => $request->message,
            ]);
        }

        // Logger l'action
        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'send_system_notification',
            'model_type' => 'Notification',
            'model_id' => null,
            'nouvelle_val' => [
                'target' => $request->user_id,
                'titre' => $request->titre,
            ],
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', 'Notification système envoyée avec succès!');
    }
}
