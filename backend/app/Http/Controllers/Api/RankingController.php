<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\PlayerStatsService;
use Illuminate\Http\Request;

class RankingController extends Controller
{
    public function season(Request $request)
    {
        $validated = $request->validate([
            'league_id' => ['required', 'integer'],
            'season_id' => ['required', 'integer'],
        ]);

        $ranking = PlayerStatsService::seasonRanking(
            $validated['league_id'],
            $validated['season_id']
        );

        return response()->json([
            'league_id' => $validated['league_id'],
            'season_id' => $validated['season_id'],
            'ranking'   => $ranking,
        ]);
    }

}
