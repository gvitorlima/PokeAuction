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
        "pokemon_id",
        "status_id",
        "value"
    ];

    public function createPokemonStatus(Pokemon $pokemon, EnumPokemonStatus $pokemonStatus, int $value)
    {
        $this->pokemon_id = $pokemon->pokemon_id;
        $this->status_id = $pokemonStatus->value;
        $this->value = $value;
    }
}
