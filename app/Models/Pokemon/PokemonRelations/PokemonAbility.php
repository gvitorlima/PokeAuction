<?php

namespace App\Models\Pokemon\PokemonRelations;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PokemonAbility extends Model
{
    use HasFactory;

    protected $timestamps = false;
    protected $table = 'pokemon_ability';

    protected $fillable = [
        'pokemon_id',
        'ability_id'
    ];
}
