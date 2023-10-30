<?php

namespace App\Models\Pokemon;

use App\Models\Pokemon\PokemonRelations\PokemonType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pokemon extends Model
{
    use HasFactory;

    protected $id;

    protected $table = "pokemon";
    public $timestamps = false;

    protected $fillable = [
        'base_experience',
        'height',
        'pokemon_id',
        'pokemon_name',
        'weight',
    ];

    public function __construct()
    {
    }
}
