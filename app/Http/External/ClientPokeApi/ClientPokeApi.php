<?php

namespace App\Http\External\ClientPokeApi;

use App\Contracts\Client;
use GuzzleHttp\Client as GuzzleHttpClient;

class ClientPokeApi extends Client
{

    private string
        $uri,
        $increaseValue;

    private int
        $numberOfPokemonsForAuction,
        $numberOfExistingPokemons;

    public function __construct(GuzzleHttpClient $client)
    {
        $this->client = $client;

        $this->uri = env("POKE_API_URI");
        $this->increaseValue = env("INCREASE_POKEMON_VALUE");

        $this->numberOfPokemonsForAuction = env("DAILY_QUANTITY");
        $this->numberOfExistingPokemons = env("POKE_API_QUANTITY");
    }
}
