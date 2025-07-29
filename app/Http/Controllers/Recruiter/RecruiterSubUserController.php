<?php

namespace App\Http\Controllers\Recruiter;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class RecruiterSubUserController extends Controller
{
    public function index()
    {
        $subUsers = User::where('parent_id', Auth::id())->get();
        return view('recruiter.sub_users.index', compact('subUsers'));
    }

    public function create()
    {
        return view('recruiter.sub_users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'permissions' => 'array',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => 'recruiter_assistant',
            'password' => bcrypt($request->password),
            'permissions' => $request->permissions,
            'parent_id' => Auth::id(),
        ]);

        return redirect()->route('recruiter.subuser.index')->with('success', 'Sub-user added.');
    }

    public function edit($id)
    {
        $user = User::where('id', $id)->where('parent_id', Auth::id())->firstOrFail();
        $availablePermissions = ['create_job', 'view_applied_users', 'manage_all_jobs', 'manage_applications', 'manage_team', 'manage_blog', 'manage_interview'];
        return view('recruiter.sub_users.edit', compact('user','availablePermissions'));
    }

    public function update(Request $request, $id)
    {
        $user = User::where('id', $id)->where('parent_id', Auth::id())->firstOrFail();

        $request->validate([
            'name' => 'required',
            'permissions' => 'array',
        ]);

        $user->update([
            'name' => $request->name,
            'permissions' => $request->permissions,
        ]);

        return redirect()->route('recruiter.subuser.index')->with('success', 'Sub-user updated.');
    }

    public function destroy($id)
    {
        $user = User::where('id', $id)->where('parent_id', Auth::id())->firstOrFail();
        $user->delete();
        return redirect()->route('recruiter.subuser.index')->with('success', 'Sub-user deleted.');
    }
}
