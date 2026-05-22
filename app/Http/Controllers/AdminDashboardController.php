<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Recruteur;
use App\Models\Offre;
use App\Models\Candidature;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminDashboardController extends Controller
{
    /**
     * Display the admin dashboard with stats.
     */
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'total_recruteurs' => Recruteur::count(),
            'total_offres' => Offre::count(),
            'total_candidatures' => Candidature::count(),
            'pending_verifications' => Recruteur::where('is_verified', false)->count(),
        ];

        $recent_logs = AuditLog::with('user')->latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recent_logs'));
    }

    /**
     * List all recruiters and their verification status.
     */
    public function recruiters()
    {
        $recruiters = Recruteur::with('user')->paginate(10);
        return view('admin.recruiters', compact('recruiters'));
    }

    /**
     * Verify or reject a recruiter.
     */
    public function verifyRecruiter(Request $request, Recruteur $recruteur)
    {
        $request->validate([
            'action' => 'required|in:approve,reject',
        ]);

        if ($request->action === 'approve') {
            $recruteur->update(['is_verified' => true]);
            
            AuditLog::create([
                'user_id' => Auth::id(),
                'action' => 'APPROVE_RECRUITER',
                'model_type' => 'Recruteur',
                'model_id' => $recruteur->id,
                'nouvelle_val' => ['is_verified' => true],
                'ip_address' => $request->ip(),
            ]);

            return back()->with('success', 'Recruteur approuvé avec succès.');
        } else {
            $recruteur->update(['is_verified' => false]); // Or handle rejection logic

            AuditLog::create([
                'user_id' => Auth::id(),
                'action' => 'REJECT_RECRUITER',
                'model_type' => 'Recruteur',
                'model_id' => $recruteur->id,
                'nouvelle_val' => ['is_verified' => false],
                'ip_address' => $request->ip(),
            ]);

            return back()->with('success', 'Recruteur rejeté.');
        }
    }

    /**
     * List all users.
     */
    public function users()
    {
        $users = User::paginate(15);
        return view('admin.users', compact('users'));
    }

    /**
     * Toggle user active status.
     */
    public function toggleUserStatus(Request $request, User $user)
    {
        $user->is_active = !$user->is_active;
        $user->save();

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => $user->is_active ? 'ACTIVATE_USER' : 'DEACTIVATE_USER',
            'model_type' => 'User',
            'model_id' => $user->id,
            'nouvelle_val' => ['is_active' => $user->is_active],
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', 'Statut de l\'utilisateur mis à jour.');
    }

    /**
     * Display audit logs.
     */
    public function logs()
    {
        $logs = AuditLog::with('user')->latest()->paginate(20);
        return view('admin.logs', compact('logs'));
    }
}
