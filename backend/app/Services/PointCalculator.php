<?php

namespace App\Services;

use App\Models\Rule;

class PointCalculator
{
    public static function calculate(
        int $score,
        float $rankValue,
        Rule $rule,
        int $mistakeCount = 0
    ): float {
        // 素点計算
        $base = ($score - $rule->return_score) / 1000;

        // ウマ配列
        $umaMap = [
            1 => $rule->uma_1,
            2 => $rule->uma_2,
            3 => $rule->uma_3,
            4 => $rule->uma_4,
        ];

        // 同順位ウマ平均
        $rankStart = (int) floor($rankValue);
        $rankEnd   = (int) ceil($rankValue);

        $umaSum = 0;
        for ($i = $rankStart; $i <= $rankEnd; $i++) {
            $umaSum += $umaMap[$i];
        }

        $avgUma = $umaSum / ($rankEnd - $rankStart + 1);

        // チョンボ（-20固定）
        $penalty = $mistakeCount * 20;

        return $base + $avgUma - $penalty;
    }
}
