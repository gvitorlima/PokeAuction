<?php

namespace App\Models\Pokemon\PokemonRelations;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class Ability extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = "ability";
    protected $fillable = [
        "name"
    ];

    public function getByName(string $name): Builder
    {
        return self::where('name', $name);
    }
}
