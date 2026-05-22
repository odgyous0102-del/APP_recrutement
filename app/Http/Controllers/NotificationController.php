<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Marque toutes les notifications comme lues.
     */
    public function markAllAsRead()
    {
        Auth::user()->notifications()->whereNull('lu_at')->update(['lu_at' => now()]);
        return back()->with('success', 'Toutes les notifications ont été marquées comme lues.');
    }

    /**
     * Marque une notification spécifique comme lue.
     */
    public function markAsRead(Notification $notification)
    {
        if ($notification->user_id !== Auth::id()) {
            abort(403);
        }

        $notification->update(['lu_at' => now()]);

        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }

        return back();
    }
}
