<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        // Fetch notifications for the authenticated user
        $notifications = Notification::where('user_id', auth()->id())->orderBy('created_at', 'desc')->get();

        return view('notifications.index', compact('notifications'));
    }

    public function markAsRead(Notification $notification)
    {
        $notification->is_read = true;
        $notification->save();

        return redirect()->back()->with('success', 'Notification marked as read.');
    }

}
