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
        Schema::create("pokemon_status", function (Blueprint $blueprint) {
            $blueprint->id();

            $blueprint->foreignId("pokemon_id")->constrained("pokemon");
            $blueprint->foreignId("status_id")->constrained("status");

            $blueprint->integer("value", 6);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("pokemon_status");
    }
};
