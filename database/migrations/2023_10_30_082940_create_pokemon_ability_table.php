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

        Schema::create("pokemon_ability", function (Blueprint $blueprint) {
            $blueprint->id();

            $blueprint->foreignId("pokemon_id")->constrained("pokemon", "pokemon_id");
            $blueprint->foreignId("ability_id")->constrained("ability", "id");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("pokemon_ability");
    }
};
