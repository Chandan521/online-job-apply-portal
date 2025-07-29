<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SalariesController extends Controller
{
    public function salaries(Request $request)
    {
        $jobs = DB::table('jobs')
            ->select(
                'title',
                DB::raw('COUNT(*) as job_count'),
                DB::raw('MIN(CAST(REPLACE(REPLACE(salary, "₹", ""), " ", "") AS UNSIGNED)) as min_salary'),
                DB::raw('MAX(CAST(REPLACE(REPLACE(salary, "₹", ""), " ", "") AS UNSIGNED)) as max_salary'),
                DB::raw('GROUP_CONCAT(salary SEPARATOR ", ") as all_salaries')
            )
            ->groupBy('title')
            ->orderByDesc('job_count')
            ->get();

        return view('salaries', compact('jobs'));
    }
}
