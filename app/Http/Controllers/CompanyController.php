<?php

namespace App\Http\Controllers;

use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CompanyController extends Controller
{
    public function company(Request $request)
    {
        $search = $request->q;

        $companies = DB::table('reviews')
            ->join('jobs', 'reviews.job_id', '=', 'jobs.id')
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('jobs.company', 'like', '%' . $search . '%')
                        ->orWhere('jobs.title', 'like', '%' . $search . '%');
                });
            })
            ->select(
                'jobs.id',
                'jobs.company',
                'jobs.salary',
                'jobs.company_logo',
                DB::raw('AVG(reviews.rating) as avg_rating'),
                DB::raw('COUNT(reviews.id) as total_reviews')
            )
            ->groupBy('jobs.company', 'jobs.salary', 'jobs.company_logo', 'jobs.id')
            ->orderByDesc('avg_rating')
            ->get();

        return view('company', compact('companies'));
    }
}
