<?php

namespace App\Models\Pokemon;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pokemon extends Model
{
    use HasFactory;

    protected $table = "POKEMON";

    public function pokemon(int $pokemonId)
    {
    }

    public function pokemonAbilities(int $pokemonId)
    {
    }

    public function pokemonTypes(int $pokemonId)
    {
    }
}
