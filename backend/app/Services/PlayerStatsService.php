<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class PlayerStatsService
{
    private static function baseSeasonQuery(int $leagueId, int $seasonId)
    {
        return DB::table('game_players as gp')
            ->join('games as g', 'g.id', '=', 'gp.game_id')
            ->join('users as u', 'u.id', '=', 'gp.user_id')
            ->where('g.league_id', $leagueId)
            ->where('g.season_id', $seasonId)
            ->groupBy('gp.user_id', 'u.name')
            ->selectRaw('
                gp.user_id,
                u.name,
                COUNT(*) as games_count,
                COALESCE(SUM(gp.point), 0) as total_point,
                COALESCE(AVG(gp.rank_value), 0) as avg_rank,
                COALESCE(SUM(gp.mistake_count), 0) as total_mistakes,
                SUM(CASE WHEN gp.rank = 1 THEN 1 ELSE 0 END) as win_count
            ');
    }

    public static function seasonStats(int $leagueId, int $seasonId)
    {
        return self::baseSeasonQuery($leagueId, $seasonId)
            ->orderByDesc('total_point')
            ->get();
    }

    public static function seasonRanking(int $leagueId, int $seasonId)
    {
        return self::baseSeasonQuery($leagueId, $seasonId)
            ->orderByDesc('total_point')
            ->orderBy('avg_rank')
            ->orderByDesc('win_count')
            ->orderByDesc('games_count')
            ->orderBy('total_mistakes')
            ->get();
    }

}
