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
        Schema::create('pokemon_type', function (Blueprint $blueprint) {
            $blueprint->increments('id');

            $blueprint->foreignId('pokemon_id')->constrained('pokemon', 'pokemon_id');
            $blueprint->foreignId('type_id')->constrained('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pokemon_type');
    }
};
