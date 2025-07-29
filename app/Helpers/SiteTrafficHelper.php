<?php

namespace App\Helpers;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SiteTrafficHelper
{
    public static function getSiteTrafficData($days = 7)
    {
        $labels = collect(range($days - 1, 0))->map(function ($i) {
            return Carbon::now()->subDays($i)->format('d M');
        });

        $visits = collect(range($days - 1, 0))->map(function ($i) {
            return DB::table('users')
                ->whereDate('created_at', Carbon::now()->subDays($i))
                ->whereIn('role', ['recruiter', 'job_seeker'])
                ->count();
        });

        return [
            'labels' => $labels,
            'visits' => $visits,
        ];
    }
}
