<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index()
    {
        $companies = \App\Models\Job::select('company', 'company_logo')
            ->distinct()
            ->whereNotNull('company')
            ->where('company', '!=', '')
            ->get();

        return view('admin.companies.index', compact('companies'));
    }
}
