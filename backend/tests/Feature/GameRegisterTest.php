<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Rule;
use App\Models\League;
use App\Models\Season;
use App\Models\GamePlayer;
use App\Services\GameRegister;

class GameRegisterTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 同点がある場合_ウマが平均分配され_チョンボが減算される()
    {
        // ① 事前データ
        $league = League::create(['name' => 'テストリーグ']);

        $season = Season::create([
            'league_id' => $league->id,
            'name' => '2025',
            'start_date' => '2025-01-01',
            'end_date' => '2025-12-31',
        ]);

        $rule = Rule::create([
            'name' => '標準東南',
            'start_score' => 25000,
            'return_score' => 30000,
            'uma_1' => 30,
            'uma_2' => 10,
            'uma_3' => -10,
            'uma_4' => -30,
        ]);

        $users = collect(['A','B','C','D'])->map(function ($name) {
            return User::create([
                'name' => $name,
                'email' => strtolower($name).'@test.com',
                'password' => bcrypt('password'),
            ]);
        });

        // ② 実行
        GameRegister::register(
            leagueId: $league->id,
            seasonId: $season->id,
            rule: $rule,
            playDate: '2025-01-05',
            round: 1,
            players: [
                ['user_id'=>$users[0]->id,'seat'=>1,'score'=>42000],
                ['user_id'=>$users[1]->id,'seat'=>2,'score'=>42000],
                ['user_id'=>$users[2]->id,'seat'=>3,'score'=>20000],
                ['user_id'=>$users[3]->id,'seat'=>4,'score'=>8000,'mistake_count'=>1],
            ]
        );

        // ③ 検証
        $players = GamePlayer::orderBy('seat')->get();

        // 1位同点（30+10 / 2 = 20）
        // (42000 - 30000) / 1000 = 12
        // 12 + 20 = 32
        $this->assertEquals(32.0, $players[0]->point);
        $this->assertEquals(32.0, $players[1]->point);

        // 3位
        // (20000 - 30000) / 1000 = -10
        // -10 + -10 = -20
        $this->assertEquals(-20.0, $players[2]->point);

        // 4位 + チョンボ
        // (8000 - 30000) / 1000 = -22
        // -22 + -30 = -52
        // -52 - 20 = -72
        $this->assertEquals(-72.0, $players[3]->point);

        // 同順順位
        $this->assertEquals(1.5, $players[0]->rank_value);
        $this->assertEquals(1.5, $players[1]->rank_value);
    }
}
