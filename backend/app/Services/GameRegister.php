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

            // ① Game 作成
            $game = Game::create([
                'league_id' => $leagueId,
                'season_id' => $seasonId,
                'rule_id'   => $rule->id,
                'play_date' => $playDate,
                'round'     => $round,
            ]);

            // ② スコア降順
            $sorted = collect($players)->sortByDesc('score')->values();

            // ③ 同点グループ
            $groups = $sorted->groupBy('score');

            $currentRank = 1;

            foreach ($groups as $sameScorePlayers) {
                $count = $sameScorePlayers->count();

                // この同順グループが占める順位帯
                $ranks = range($currentRank, $currentRank + $count - 1);

                // 平均順位（表示用）
                $rankValue = array_sum($ranks) / count($ranks);

                foreach ($sameScorePlayers as $p) {
                    $point = PointCalculator::calculate(
                        $p['score'],
                        $ranks,
                        $rule
                    );

                    // チョンボは最後に減算
                    $finalPoint = $point - (($p['mistake_count'] ?? 0) * 20);

                    GamePlayer::create([
                        'game_id'       => $game->id,
                        'user_id'       => $p['user_id'],
                        'seat'          => $p['seat'],
                        'score'         => $p['score'],
                        'rank'          => $currentRank,
                        'rank_value'    => $rankValue,
                        'point'         => $finalPoint,
                        'mistake_count' => $p['mistake_count'] ?? 0,
                    ]);
                }

                $currentRank += $count;
            }

            return $game;
        });
    }
}
