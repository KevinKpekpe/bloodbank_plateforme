<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Afficher la liste des notifications de l'utilisateur
     */
    public function index()
    {
        $notifications = Auth::user()->customNotifications()
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('notifications.index', compact('notifications'));
    }

    /**
     * Marquer une notification comme lue
     */
    public function markAsRead(Notification $notification)
    {
        // Vérifier que la notification appartient à l'utilisateur connecté
        if ($notification->user_id !== Auth::id()) {
            abort(403);
        }

        $notification->markAsRead();

        return response()->json(['success' => true]);
    }

    /**
     * Marquer toutes les notifications comme lues
     */
    public function markAllAsRead()
    {
        Auth::user()->customNotifications()->unread()->update(['read_at' => now()]);

        return response()->json(['success' => true]);
    }

    /**
     * Supprimer une notification
     */
    public function destroy(Notification $notification)
    {
        // Vérifier que la notification appartient à l'utilisateur connecté
        if ($notification->user_id !== Auth::id()) {
            abort(403);
        }

        $notification->delete();

        return response()->json(['success' => true]);
    }

    /**
     * Obtenir le nombre de notifications non lues (AJAX)
     */
    public function unreadCount()
    {
        $count = Auth::user()->customNotifications()->unread()->count();

        return response()->json(['count' => $count]);
    }

    /**
     * Obtenir les dernières notifications non lues (AJAX)
     */
    public function latest()
    {
        $notifications = Auth::user()->customNotifications()
            ->unread()
            ->latest()
            ->limit(5)
            ->get();

        return response()->json($notifications);
    }
}
