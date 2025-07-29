<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = User::query();

            if ($request->name) {
                $data->where('name', 'like', '%' . $request->name . '%');
            }

            if ($request->role) {
                $data->where('role', $request->role);
            }

            if ($request->status) {
                $data->where('status', $request->status);
            }

            return Datatables::of($data)
                ->addColumn('action', function ($row) {
                    $btn = '<div class="d-flex justify-content-end">';
                    $btn .= '<a href="' . route('users.show', $row->id) . '" class="btn btn-sm btn-icon btn-light-primary me-2"><i class="bi bi-eye"></i></a>';
                    $btn .= '<a href="' . route('users.edit', $row->id) . '" class="btn btn-sm btn-icon btn-light-warning me-2"><i class="bi bi-pencil"></i></a>';
                    $btn .= '<form action="' . route('users.destroy', $row->id) . '" method="POST" class="d-inline" onsubmit="return confirm(\'Are you sure?\');">';
                    $btn .= csrf_field();
                    $btn .= method_field('DELETE');
                    $btn .= '<button type="submit" class="btn btn-sm btn-icon btn-light-danger"><i class="bi bi-trash"></i></button>';
                    $btn .= '</form>';
                    $btn .= '</div>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.users.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            'role' => 'required',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $id,
            'role' => 'required',
            'status' => 'required',
            'profile_photo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $data = $request->except('profile_photo');

        if ($request->hasFile('profile_photo')) {
            $path = $request->file('profile_photo')->store('public/profile_photos');
            $data['profile_photo'] = str_replace('public/', '', $path);
        }
        $user->update($data);

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }
}
