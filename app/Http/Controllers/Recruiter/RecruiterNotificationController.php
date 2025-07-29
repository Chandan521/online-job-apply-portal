<?php

namespace App\Http\Controllers\Recruiter;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class RecruiterNotificationController extends Controller
{
    public function markAsRead($id)
    {
        DB::table('notifications')
            ->where('id', $id)
            ->where('user_id', auth()->id())
            ->update(['is_read' => true]);

        return back()->with('status', 'Notification marked as read.');
    }

    public function delete($id)
    {
        DB::table('notifications')
            ->where('id', $id)
            ->where('user_id', auth()->id())
            ->delete();

        return back()->with('status', 'Notification deleted.');
    }
}
