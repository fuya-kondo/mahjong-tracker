<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    protected $fillable = [
        'league_id',
        'season_id',
        'rule_id',
        'play_date',
        'round',
    ];

    public function players()
    {
        return $this->hasMany(GamePlayer::class);
    }

    public function rule()
    {
        return $this->belongsTo(Rule::class);
    }
}

