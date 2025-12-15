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
        Schema::create('rules', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->integer('start_score');
            $table->integer('return_score');

            $table->tinyInteger('uma_1');
            $table->tinyInteger('uma_2');
            $table->tinyInteger('uma_3');
            $table->tinyInteger('uma_4');

            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rules');
    }
};
