<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rule extends Model
{
    protected $fillable = [
        'name',
        'start_score',
        'return_score',
        'uma_1',
        'uma_2',
        'uma_3',
        'uma_4',
    ];
}
