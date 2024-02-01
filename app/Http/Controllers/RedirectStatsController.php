<?php
// app/Http/Controllers/RedirectStatsController.php

namespace App\Http\Controllers;

use App\Models\Redirect;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Adicione esta linha

class RedirectStatsController extends Controller
{
    public function show(Request $request, Redirect $redirect)
    {
        $totalAccesses = $redirect->logs()->count();
        $uniqueAccesses = $redirect->logs()->distinct('ip')->count('ip');
        $topReferrers = $this->getTopReferrers($redirect);
        $lastTenDaysAccesses = $this->getLastTenDaysAccesses($redirect);

        $stats = [
            'total_accesses' => $totalAccesses,
            'unique_accesses' => $uniqueAccesses,
            'top_referrers' => $topReferrers,
            'last_ten_days_accesses' => $lastTenDaysAccesses,
        ];

        return response()->json(['stats' => $stats]);
    }

    private function getTopReferrers(Redirect $redirect)
    {
        return $redirect->logs()
            ->groupBy('referer')
            ->orderByDesc(DB::raw('count(*)'))
            ->take(5)
            ->get(['referer', DB::raw('count(*) as count')]);
    }

    private function getLastTenDaysAccesses(Redirect $redirect)
    {
        $dates = [];
        $accesses = [];

        for ($i = 9; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $dates[] = $date;

            $accessesForDate = $redirect->logs()->whereDate('accessed_at', $date)->count();
            $uniqueAccessesForDate = $redirect->logs()->whereDate('accessed_at', $date)->distinct('ip')->count('ip');

            $accesses[] = [
                'date' => $date,
                'total' => $accessesForDate,
                'unique' => $uniqueAccessesForDate,
            ];
        }

        return $accesses;
    }
}
