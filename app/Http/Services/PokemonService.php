<?php

namespace App\Http\Services;

use App\Http\External\ClientPokeApi\ClientPokeApi;
use App\Http\External\ClientPokeApi\ClientPokeApiDto;
use Exception;

class PokemonService
{
    private string
        $uri;

    private int
        $numberOfExistingPokemons;

    public function __construct(
        private ClientPokeApi $client,
        private ClientPokeApiDto $clientDto
    ) {

        $this->uri = env("POKE_API_URI");

        $this->numberOfExistingPokemons = env("POKE_API_QUANTITY");
    }

    public function pokemonById(int $pokemonId)
    {
        if ($pokemonId > $this->numberOfExistingPokemons || $pokemonId < 1)
            throw new Exception("Pokemon ID inexistente", 400);

        // TODO Realizar a busca com o banco de dados antes de usar a API, banco incompleto ainda!

        $pokemon =  $this->requestExternalService($pokemonId);
        return $pokemon;
    }

    public function pokemonByName(string $pokemonName)
    {
        $pokemon =  $this->requestExternalService($pokemonName);
    }

    /**
     * Monta a URL de busca com base no ID ou NOME de um Pokemon
     *
     * @param int|string $searchParameter,  Parâmetro de busca associado a um pokemon podendo
     *                                      ser seu NOME ou ID
     */
    private function requestExternalService(int|string $searchParameter)
    {
        $pokemon = $this->client->sendRequest($this->mountPokemonUrlById($searchParameter), "GET");
        $pokemon = $this->clientDto->padronize(
            $pokemon->getBody()
                ->getContents()
        );

        return $pokemon;
    }

    /**
     * Concatena a URL de pesquisa com o ID ou NOME do pokemon
     *
     * @param int $searchParam, ID do pokemon
     */
    private function mountPokemonUrlById(int|string $searchParam): string
    {
        return $this->uri . "/$searchParam";
    }
}
