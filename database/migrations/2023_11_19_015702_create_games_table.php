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
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->tinyInteger('result')->comment('0: loss, 1: win, 2: draw');
            $table->string('map_played');
            $table->foreignId('map_played_id')->constrained('overwatch_maps', 'id');
            $table->string('game_mode')->default('Competitive')->comment("Quick Play or Competitive");;
            $table->string('game_type')->comment("Control, Escort, Hybrid, Push, Flashpoint");
            $table->string('map_section_1')->nullable();
            $table->string('map_section_2')->nullable();
            $table->string('map_section_3')->nullable();
            $table->tinyInteger('round_1_outcome')->nullable()->comment('0: loss, 1: win, null: Not Played');
            $table->tinyInteger('round_2_outcome')->nullable()->comment('0: loss, 1: win, null: Not Played');
            $table->tinyInteger('round_3_outcome')->nullable()->comment('0: loss, 1: win, null: Not Played');
            $table->unsignedTinyInteger('round_wins')->default(0);
            $table->unsignedTinyInteger('round_losses')->default(0);
            $table->string('hero_played');
            $table->string('additional_hero_played_1')->nullable();
            $table->string('additional_hero_played_2')->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('game_role', ['Tank', 'Damage', 'Support']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('games');
    }
};
