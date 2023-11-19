<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Read the map data
        $maps = require database_path('data/OverwatchMaps.php');

        // Extract unique names and types
        $names = array_unique(array_column($maps, 'name'));
        $types = array_unique(array_column($maps, 'type'));

        Schema::create('overwatch_maps', function (Blueprint $table) use ($names, $types) {
            $table->id();
            $table->enum('name', $names);
            $table->enum('type', $types);
            $table->string('area_1')->nullable();
            $table->string('area_2')->nullable();
            $table->string('area_3')->nullable();
            $table->string('country');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('overwatch_maps');
    }
};
