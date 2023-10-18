<?php

namespace App\Models\Pokemon\Relations;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ability extends Model
{
    use HasFactory;

    protected $table = "POKEMON_ABILITY";
}
