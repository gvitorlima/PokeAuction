<?php

namespace Database\Seeders;

use App\Enums\PokemonStatus as EnumPokemonStatus;
use App\Models\Pokemon\PokemonRelations\Status;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusSeeder extends Seeder
{

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /**
         * Verifica se os dados já foram inseridos antes de realizar a inserção
         */
        $totalRecords = DB::table('status')->count();
        if (!$totalRecords > 0) {

            Status::factory()->createMany([
                EnumPokemonStatus::seedStatus()
            ]);
        }
    }
}
