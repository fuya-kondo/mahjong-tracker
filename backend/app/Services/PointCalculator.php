<?php

namespace App\Services;

use App\Models\Rule;

class PointCalculator
{
    /**
     * @param int $score 素点
     * @param array<int> $ranks 同順位グループ内の順位リスト（例: [1,2]）
     * @param Rule $rule
     */
    public static function calculate(
        int $score,
        array $ranks,
        Rule $rule
    ): float {
        // ① 素点 → 持ち点計算
        $base = ($score - $rule->return_score) / 1000;

        // ② 同順ウマ平均
        $umaSum = 0;
        foreach ($ranks as $rank) {
            $umaSum += match ($rank) {
                1 => $rule->uma_1,
                2 => $rule->uma_2,
                3 => $rule->uma_3,
                4 => $rule->uma_4,
            };
        }

        $umaAvg = $umaSum / count($ranks);

        return $base + $umaAvg;
    }
}
