<?php

namespace App\Models\Pokemon\PokemonRelations;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ability extends Model
{
    use HasFactory;

    protected $timestamps = false;
    protected $table = 'ability';
    protected $fillable = [
        'id',
        'name'
    ];
}
