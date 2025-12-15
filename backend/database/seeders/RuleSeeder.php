<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Rule;

class RuleSeeder extends Seeder
{
    public function run(): void
    {
        Rule::create([
            'name' => 'Mリーグ',
            'start_score' => 25000,
            'return_score' => 30000,
            'uma_1' => 30,
            'uma_2' => 10,
            'uma_3' => -10,
            'uma_4' => -30,
        ]);

        Rule::create([
            'name' => '最高位戦',
            'start_score' => 30000,
            'return_score' => 30000,
            'uma_1' => 30,
            'uma_2' => 10,
            'uma_3' => -10,
            'uma_4' => -30,
        ]);
    }
}
