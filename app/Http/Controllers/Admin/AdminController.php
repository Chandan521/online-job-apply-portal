<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.admin_login_register');
    }

    public function admin_login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $remember = $request->filled('remember'); 

        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();
            if ($user->role === 'admin') {
                $user->last_login_at = now();
                $user->last_login_ip = $request->ip();
                $user->save();
                return redirect()->route('admin.dashboard');
            }
        }

        return redirect()->back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->withInput($request->only('email', 'remember'))->with('form', 'login');
    }

    public function admin_register(Request $request)
    {
        try {
            $request->validate([
                'firstname' => ['required', 'string', 'max:255'],
                'lastname' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => ['required', 'string', 'min:8', 'confirmed'],
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput()
                ->with('form', 'register');
        }

        $user = User::create([
            'name' => $request->firstname . ' ' . $request->lastname,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'admin',
        ]);

        Auth::login($user);

        return redirect()->route('admin.dashboard');
    }


    public function jobs()
    {
        return view('admin.jobs.index');
    }

    public function candidates()
    {
        return view('admin.candidates.index');
    }

    public function companies()
    {
        return view('admin.companies.index');
    }

    public function analytics()
    {
        return view('admin.analytics.index');
    }

    
}