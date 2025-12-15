<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GamePlayer extends Model
{
    protected $fillable = [
        'game_id',
        'user_id',
        'seat',
        'rank',
        'rank_value',
        'score',
        'point',
        'mistake_count',
    ];
}
