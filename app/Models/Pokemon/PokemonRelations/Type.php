<?php

namespace App\Models\Pokemon\PokemonRelations;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    use HasFactory;

    protected $table = "type";
    public $timestamps = false;

    protected $fillable = [
        'id',
        'name'
    ];
}
