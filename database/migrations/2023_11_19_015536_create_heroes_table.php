<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Read the hero data
        $heroes = require database_path('data/OverwatchHeroes.php');

        // Extract unique names and types
        $names = array_unique(array_column($heroes, 'name'));
        $types = array_unique(array_column($heroes, 'type'));

        Schema::create('overwatch_heroes', function (Blueprint $table) use ($names, $types) {
            $table->id();
            $table->enum('name', $names);
            $table->enum('type', $types);
            $table->integer('type_id');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('heroes');
    }
};
