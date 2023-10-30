<?php

namespace App\Models\Pokemon\PokemonRelations;

use App\Models\Pokemon\Pokemon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class PokemonAbility extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'pokemon_ability';

    protected $fillable = [
        'pokemon_id',
        'ability_id'
    ];

    public function createPokemonAbility(Pokemon $pokemon, Ability $ability)
    {
        $this->pokemon_id = $pokemon->pokemon_id;
        $this->ability_id = $ability->id;
    }
}
