<?php

namespace App\Http\Controllers;

use App\Http\Services\PokemonService;
use Illuminate\Http\Request;

use Illuminate\Routing\Controller;

class PokemonController extends Controller
{
    public function __construct(
        PokemonService $pokemonService
    ) {
    }

    public function pokemonById(int $pokemonId)
    {
    }

    public function pokemonByName(string $pokemonName)
    {
    }
}
