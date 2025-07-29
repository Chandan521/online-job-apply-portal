<?php

namespace App\Http\Controllers\Admin;

use App\Models\BannedIp;
use App\Models\UserReport;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserReportController extends Controller
{
    public function index()
    {
        $reports = UserReport::with(['toUser', 'fromUser'])->latest()->get();
        return view('admin.reports.index', compact('reports'));
    }

    public function ban($id)
    {
        $report = UserReport::findOrFail($id);

        $reportedUser = $report->toUser;

        if ($reportedUser) {
            $reportedUser->update([
                'status' => 'banned',
            ]);

            return back()->with('success', 'User with email ' . $reportedUser->email . ' has been blocked successfully.');
        }

        return back()->with('error', 'Reported user not found.');
    }
}
