<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminCandidateController extends Controller
{
    public function index()
    {
        $candidates = \App\Models\JobApplication::with(['user', 'job'])
            ->latest()
            ->get();

        return view('admin.candidates.index', compact('candidates'));
    }
}
