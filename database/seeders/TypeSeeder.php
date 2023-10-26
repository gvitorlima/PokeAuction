<?php

namespace Database\Seeders;

use App\Enums\PokemonType as EnumPokemonType;
use App\Models\Pokemon\PokemonRelations\Type;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TypeSeeder extends Seeder
{
    public function run()
    {
        /**
         * Verifica se os dados já foram inseridos
         */
        $totalRecords = DB::table('type')->count();
        if (!$totalRecords > 0)
            Type::factory()->createMany(EnumPokemonType::seed());
    }
}
