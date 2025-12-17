<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreGameRequest;
use App\Models\Rule;
use App\Services\GameRegister;

class GameController extends Controller
{
    public function store(StoreGameRequest $request)
    {
        $rule = Rule::findOrFail($request->rule_id);

        $game = GameRegister::register(
            leagueId: $request->league_id,
            seasonId: $request->season_id,
            rule: $rule,
            playDate: $request->play_date,
            round: $request->round,
            players: $request->players,
        );

        return response()->json([
            'game_id' => $game->id,
        ], 201);
    }
}
