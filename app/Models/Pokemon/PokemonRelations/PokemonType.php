<?php

namespace App\Models\Pokemon\PokemonRelations;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PokemonType extends Model
{
    use HasFactory;

    protected $table = 'type';
    protected $timestamps = false;

    protected $fillable = [
        'pokemon_id',
        'type_id'
    ];
}
