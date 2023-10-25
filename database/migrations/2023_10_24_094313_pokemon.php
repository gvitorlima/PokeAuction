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
        Schema::create('pokemon', function (Blueprint $blueprint) {
            $blueprint->increments('id');

            $blueprint->integer('pokemon_id', false);

            $blueprint->float('base_experience', 6)->nullable();
            $blueprint->float('height', 6);
            $blueprint->float('weight', 6);

            $blueprint->string('pokemon_name', 128);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pokemon');
    }
};
