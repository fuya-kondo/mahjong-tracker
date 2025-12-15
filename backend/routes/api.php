<?php 

use App\Http\Controllers\Api\RankingController;

Route::get('/ranking/season', [RankingController::class, 'season']);
