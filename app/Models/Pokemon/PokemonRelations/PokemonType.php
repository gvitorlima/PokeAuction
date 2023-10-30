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

    public function createPokemonType(Pokemon $pokemon, EnumPokemonType $type)
    {
        $this->pokemon_id = $pokemon->pokemon_id;
        $this->type_id = $type->value;
    }

    // public function pokemon()
    // {
    //     return $this->hasOne(Pokemon::class, 'pokemon_id', 'pokemon_id');
    // }

    // public function type()
    // {
    //     return $this->hasMany(Type::class, 'id', 'type_id');
    // }
}
