<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Notification;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::where(
            'user_id',
            auth()->id()
        )
        ->latest()
        ->get();

        $unreadCount = Notification::where(
            'user_id',
            auth()->id()
        )
        ->where('is_read', false)
        ->count();

        return view(
            'member.notifications.index',
            compact(
                'notifications',
                'unreadCount'
            )
        );
    }

    public function markAsRead(Notification $notification)
    {
        if ($notification->user_id !== auth()->id()) {
            abort(403);
        }

        $notification->update([
            'is_read' => true
        ]);

        return back();
    }
}