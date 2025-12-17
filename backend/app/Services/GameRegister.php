<?php

namespace App\Services;

use App\Models\Game;
use App\Models\GamePlayer;
use App\Models\Rule;
use Illuminate\Support\Facades\DB;

class GameRegister
{
    public static function register(
        int $leagueId,
        int $seasonId,
        Rule $rule,
        string $playDate,
        int $round,
        array $players
    ): Game {
        return DB::transaction(function () use (
            $leagueId,
            $seasonId,
            $rule,
            $playDate,
            $round,
            $players
        ) {

            $game = Game::create([
                'league_id' => $leagueId,
                'season_id' => $seasonId,
                'rule_id'   => $rule->id,
                'play_date' => $playDate,
                'round'     => $round,
            ]);

            // ① 順位計算
            $rankedPlayers = RankCalculator::calculate($players);

            // ② 保存
            foreach ($rankedPlayers as $p) {
                $point = PointCalculator::calculate(
                    $p['score'],
                    $p['rank_value'],
                    $rule,
                    $p['mistake_count'] ?? 0
                );

                GamePlayer::create([
                    'game_id'       => $game->id,
                    'user_id'       => $p['user_id'],
                    'seat'          => $p['seat'],
                    'score'         => $p['score'],
                    'rank'          => $p['rank'],
                    'rank_value'    => $p['rank_value'],
                    'point'         => $point,
                    'mistake_count' => $p['mistake_count'] ?? 0,
                ]);
            }

            return $game;
        });
    }
}
