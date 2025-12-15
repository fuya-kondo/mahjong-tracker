<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('game_players', function (Blueprint $table) {
            $table->id();

            $table->foreignId('game_id')->constrained()->cascadeOnDelete();

            // 成績を消したくないので user はCASCADEしない
            $table->foreignId('user_id')->constrained();

            $table->unsignedTinyInteger('seat');          // 1:東 2:南 3:西 4:北
            $table->unsignedTinyInteger('rank');          // 表示用順位（1,1,3,4）
            $table->decimal('rank_value', 3, 1);          // 集計用順位（1.0,1.5,2.0,2.5,3.0,3.5,4.0）

            $table->integer('score');                     // 素点
            $table->decimal('point', 6, 1);               // 精算ポイント
            $table->unsignedTinyInteger('mistake_count')->default(0); // チョンボ回数

            $table->timestamps();

            // 1ゲームにつき席順・ユーザーは一意
            $table->unique(['game_id', 'seat']);
            $table->unique(['game_id', 'user_id']);

            // 安全策（将来ONにしてもよい）
            // $table->check('seat between 1 and 4');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_players');
    }
};
