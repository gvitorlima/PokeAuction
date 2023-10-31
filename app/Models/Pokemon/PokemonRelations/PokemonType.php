<?php

namespace App\Models\Pokemon\PokemonRelations;

use App\Enums\PokemonType as EnumPokemonType;

use App\Models\Pokemon\Pokemon;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;

class PokemonType extends Model
{
    use HasFactory;

    protected $table = 'pokemon_type';
    public $timestamps = false;

    protected $fillable = [
        'pokemon_id',
        'type_id'
    ];
}
