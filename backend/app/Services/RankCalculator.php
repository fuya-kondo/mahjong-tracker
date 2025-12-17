<?php

namespace App\Services;

class RankCalculator
{
    /**
     * @param array $players
     * [
     *   ['user_id'=>1,'seat'=>1,'score'=>42000],
     *   ...
     * ]
     *
     * @return array
     * [
     *   [
     *     'user_id' => 1,
     *     'seat' => 1,
     *     'score' => 42000,
     *     'rank' => 1,
     *     'rank_value' => 1.5,
     *   ],
     *   ...
     * ]
     */
    public static function calculate(array $players): array
    {
        $sorted = collect($players)
            ->sortByDesc('score')
            ->values();

        $groups = $sorted->groupBy('score');

        $currentRank = 1;
        $result = [];

        foreach ($groups as $sameScorePlayers) {
            $count = $sameScorePlayers->count();

            // 平均順位（例：1位と2位 → 1.5）
            $rankValue = ($currentRank + ($currentRank + $count - 1)) / 2;

            foreach ($sameScorePlayers as $p) {
                $result[] = array_merge($p, [
                    'rank' => $currentRank,
                    'rank_value' => $rankValue,
                ]);
            }

            $currentRank += $count;
        }

        return $result;
    }
}
