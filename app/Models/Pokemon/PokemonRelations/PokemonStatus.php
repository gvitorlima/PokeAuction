<?php

namespace App\Models\Pokemon\PokemonRelations;

use App\Enums\PokemonStatus as EnumPokemonStatus;
use App\Models\Pokemon\Pokemon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PokemonStatus extends Model
{
    use HasFactory;

    protected $table = 'pokemon_status';
    public $timestamps = false;

    protected $fillable = [
        "attack",
        "defense",
        "hp",
        "pokemon_id",
        "specialAttack",
        "specialDefense",
        "speed"
    ];
}
