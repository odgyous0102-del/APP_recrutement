<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\OffreController;
use App\Http\Controllers\RecruteurDashboardController;
use App\Http\Controllers\CandidatDashboardController;

// Page d'accueil
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Pages Publiques
Route::get('/offres-emploi', [App\Http\Controllers\PublicController::class, 'offres'])->name('public.offres');
Route::get('/entreprises', [App\Http\Controllers\PublicController::class, 'entreprises'])->name('public.entreprises');
Route::get('/conseils-carriere', [App\Http\Controllers\PublicController::class, 'conseils'])->name('public.conseils');
Route::get('/conseils-carriere/article/{slug?}', [App\Http\Controllers\PublicController::class, 'article'])->name('public.article');

// Routes d'authentification
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register.choice');
Route::get('/register/candidat', [AuthController::class, 'showCandidatRegisterForm'])->name('register.candidat');
Route::get('/register/recruteur', [AuthController::class, 'showRecruteurRegisterForm'])->name('register.recruteur');
Route::post('/register/candidat', [AuthController::class, 'registerCandidat'])->name('register.candidat.post');
Route::post('/register/recruteur', [AuthController::class, 'registerRecruteur'])->name('register.recruteur.post');

// Routes protégées par authentification
Route::middleware(['auth'])->group(function () {
    // Notifications
    Route::post('/notifications/mark-all-read', [App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.markAllRead');
    Route::post('/notifications/{notification}/mark-as-read', [App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.markRead');

    // Tableaux de bord selon les rôles
    Route::get('/candidat/dashboard', [CandidatDashboardController::class, 'index'])->name('candidat.dashboard');
    Route::post('/candidat/postuler/{offre}', [CandidatDashboardController::class, 'postuler'])->name('candidat.postuler');
    Route::post('/candidat/profile/update', [CandidatDashboardController::class, 'updateProfile'])->name('candidat.profile.update');
    Route::post('/candidat/message/send', [CandidatDashboardController::class, 'sendMessage'])->name('candidat.message.send');
    Route::post('/candidat/offres/{offre}/favori', [CandidatDashboardController::class, 'toggleFavori'])->name('candidat.offres.favori');

    // Marquer les messages comme lus (commun)
    Route::post('/messages/{senderId}/read', [\App\Http\Controllers\MessageController::class, 'markAsRead'])->name('messages.read');

    // Expériences et Formations
    Route::post('/candidat/experiences', [CandidatDashboardController::class, 'addExperience'])->name('candidat.experiences.add');
    Route::delete('/candidat/experiences/{experience}', [CandidatDashboardController::class, 'deleteExperience'])->name('candidat.experiences.delete');
    Route::post('/candidat/formations', [CandidatDashboardController::class, 'addFormation'])->name('candidat.formations.add');
    Route::delete('/candidat/formations/{formation}', [CandidatDashboardController::class, 'deleteFormation'])->name('candidat.formations.delete');

    // Entretiens
    Route::post('/candidat/entretiens/{entretien}/confirmer', [\App\Http\Controllers\EntretienController::class, 'confirmer'])->name('candidat.entretiens.confirmer');
    Route::post('/entretiens/{entretien}/annuler', [\App\Http\Controllers\EntretienController::class, 'annuler'])->name('entretiens.annuler');

    Route::get('/recruteur/dashboard', [RecruteurDashboardController::class, 'index'])->name('recruteur.dashboard');

    Route::middleware(['auth', 'role:administrateur'])->group(function () {
        // Dashboard Administrateur
        Route::get('/admin/dashboard', [App\Http\Controllers\AdminController::class, 'index'])->name('admin.dashboard');

        // Vérification des recruteurs
        Route::get('/admin/recruteurs/verify', [App\Http\Controllers\AdminController::class, 'verifyRecruteur'])->name('admin.recruteurs.verify');
        Route::post('/admin/recruteurs/{recruteur}/approve', [App\Http\Controllers\AdminController::class, 'approveRecruteur'])->name('admin.recruteurs.approve');

        // Gestion des utilisateurs
        Route::get('/admin/users', [App\Http\Controllers\AdminController::class, 'manageUsers'])->name('admin.users');
        Route::post('/admin/users/{user}/toggle', [App\Http\Controllers\AdminController::class, 'toggleUserStatus'])->name('admin.users.toggle');

        // Logs d'audit (lecture seule)
        Route::get('/admin/logs', [App\Http\Controllers\AdminController::class, 'viewLogs'])->name('admin.logs');

        // Messages
        Route::get('/admin/messages', [App\Http\Controllers\AdminController::class, 'messages'])->name('admin.messages');
        Route::post('/admin/messages/send', [App\Http\Controllers\AdminController::class, 'sendMessage'])->name('admin.messages.send');
        Route::post('/admin/messages/{message}/read', [App\Http\Controllers\AdminController::class, 'markMessageAsRead'])->name('admin.messages.read');

        // Notifications système
        Route::get('/admin/notifications', [App\Http\Controllers\AdminController::class, 'notifications'])->name('admin.notifications');
        Route::post('/admin/notifications/send', [App\Http\Controllers\AdminController::class, 'sendSystemNotification'])->name('admin.send_notification');

        // Statistiques
        Route::get('/admin/statistics', [App\Http\Controllers\AdminController::class, 'statistics'])->name('admin.statistics');
    });

    // Routes pour les offres (uniquement pour les recruteurs)
    Route::middleware(['recruteur'])->group(function () {
        Route::get('/offres', [OffreController::class, 'index'])->name('offres.index');
        Route::post('/offres', [OffreController::class, 'store'])->name('offres.store');
        Route::get('/offres/{offre}', [OffreController::class, 'show'])->name('offres.show');
        Route::put('/offres/{offre}', [OffreController::class, 'update'])->name('offres.update');
        Route::delete('/offres/{offre}', [OffreController::class, 'destroy'])->name('offres.destroy');

        // Messagerie
        Route::post('/recruteur/messages', [\App\Http\Controllers\MessageController::class, 'store'])->name('recruteur.messages.store');

        // Candidatures
        Route::post('/recruteur/candidatures/{candidature}/read', [RecruteurDashboardController::class, 'markCandidatureAsRead'])->name('recruteur.candidatures.read');

        // Entretiens
        Route::post('/recruteur/entretiens', [\App\Http\Controllers\EntretienController::class, 'store'])->name('recruteur.entretiens.store');

        // Évaluation des candidatures
        Route::post('/recruteur/candidatures/{candidature}/evaluate', [RecruteurDashboardController::class, 'updateEvaluation'])->name('recruteur.candidatures.evaluate');

        // Évaluations détaillées d'entretiens
        Route::post('/recruteur/evaluations', [\App\Http\Controllers\EvaluationController::class, 'store'])->name('recruteur.evaluations.store');

        // Profil Recruteur
        Route::post('/recruteur/profile', [RecruteurDashboardController::class, 'updateProfile'])->name('recruteur.profile.update');

        // Réseau Recruteur
        Route::get('/recruteur/network', [RecruteurDashboardController::class, 'network'])->name('recruteur.network');
    });
});
