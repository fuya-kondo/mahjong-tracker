<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Season extends Model
{
    protected $fillable = [
        'league_id',
        'name',
        'start_date',
        'end_date',
    ];
    public function games()
    {
        return $this->hasMany(Game::class);
    }
}
