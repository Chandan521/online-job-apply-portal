<?php

namespace App\Http\Controllers\Admin;

use App\Models\BannedIp;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BannedIpController extends Controller
{
    public function index()
    {
        $bannedIps = BannedIp::latest()->paginate(10);
        return view('admin.banned_ips.index', compact('bannedIps'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'ip_address' => 'required|ip',
            'reason' => 'nullable|string|max:255',
        ]);

        BannedIp::create($request->only('ip_address', 'reason'));

        return back()->with('success', 'IP address banned successfully.');
    }

    public function destroy($id)
    {
        $ip = BannedIp::findOrFail($id);
        $ip->delete();

        return back()->with('success', 'IP address unbanned.');
    }
}
