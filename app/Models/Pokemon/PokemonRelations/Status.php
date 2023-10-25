<?php

namespace App\Models\Pokemon\PokemonRelations;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'status';
    protected $fillable = [
        'id',
        'name'
    ];
}
