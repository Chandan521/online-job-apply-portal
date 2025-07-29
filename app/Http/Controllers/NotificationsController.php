<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationsController extends Controller
{
    public function getNotification()
    {
        $notifications = Notification::where('user_id', Auth::id())
            ->where('is_read', false)
            ->latest()
            ->get();
        return view('notifications', compact('notifications'));
    }
    public function markAsRead($id)
    {
        $notification = Notification::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $notification->is_read = true;
        $notification->save();

        return back()->with('success','sucessfull read notification');
    }
}
