<?php

namespace App\Http\External;

use App\Contracts\Client;
use GuzzleHttp\ClientInterface;
use Exception;
use GuzzleHttp\Client as GuzzleHttpClient;
use stdClass;

class PokeApiClient extends Client
{

    private string
        $uri,
        $increaseValue;

    private int
        $numberOfPokemonsForAuction,
        $numberOfExistingPokemons;

    public function __construct(?ClientInterface $client = null)
    {

        $this->client = is_null($client) ? new GuzzleHttpClient : $client;

        $this->uri = env("POKE_API_URI");
        $this->increaseValue = env("INCREASE_POKEMON_VALUE");

        $this->numberOfPokemonsForAuction = env("DAILY_QUANTITY");
        $this->numberOfExistingPokemons = env("POKE_API_QUANTITY");
    }

    /**
     * Retorna os pokemons para o leilão diário
     */
    public function dailyPokemons(): array
    {
        try {

            $pokemonsId = $this->randomizePokemonsId();
            $pokemonsId = $this->handleCheckDataValidity($pokemonsId);

            $pokemons = array_map(function (int $id) {

                $pokemon = $this->sendRequest($this->mountPokemonUrlById($id), "GET");
                $pokemon = $this->extractRelevantData($pokemon->getBody()->getContents());

                return $pokemon;
            }, $pokemonsId);

            return $pokemons;
        } catch (Exception $requestError) {
            throw $requestError;
        }
    }

    /**
     * Sorteia os ID's a serem usados no leilão com base na quantidade informada
     *
     * @param int $quantity,    Informa a quantidade de ID's a serem retornados.
     *                          usado quando algum ID se repete no leilão.
     */
    private function randomizePokemonsId(int $quantity = null): array
    {
        $quantity = 0;

        $pokemonsId = [];
        for ($quantity ?? $this->numberOfPokemonsForAuction; $quantity < $this->numberOfPokemonsForAuction; $quantity++) {

            $pokemonsId[] = rand(1, $this->numberOfExistingPokemons);
        }

        return $pokemonsId;
    }

    /**
     * Concatena a URL de pesquisa com o ID do pokemon
     *
     * @param int $id, ID do pokemon
     */
    private function mountPokemonUrlById(int $id): string
    {
        return $this->uri . "/$id";
    }

    /**
     * Função recursiva que verifica um ID repetido e substitute por um novo
     *
     * @param array $pokemonsId, ID's a serem verificados
     */
    private function handleCheckDataValidity(array $pokemonsId)
    {

        if (count($pokemonsId) < $this->numberOfPokemonsForAuction) {

            $pokemonsId = array_unique($pokemonsId);
            $missingQuantity = $this->numberOfPokemonsForAuction - count($pokemonsId);

            $missingIds = $this->randomizePokemonsId($missingQuantity);
            $pokemonsId = [...$missingIds, ...$pokemonsId];

            $pokemonsId = $this->handleCheckDataValidity($pokemonsId);
        }

        return $pokemonsId;
    }

    /**
     * Extrai os dados relevantes do amontoado de dados retornados pela API
     *
     * @link https://pokeapi.co/
     *
     * @param string $pokemonData, Json contendo os dados de um pokemon
     */
    private function extractRelevantData(string $pokemonData): array
    {
        $pokemonData = json_decode($pokemonData);

        $pokemonData = [
            "id" => $pokemonData->id,
            "name"  => $pokemonData->name,
            "image" => $pokemonData->sprites->front_default,
            "value" => $this->calculateValue($pokemonData->stats)
        ];

        return $pokemonData;
    }

    /**
     * Calcula o valor de um pokemon para o leilão com base em seus status
     *
     * @param array $pokemonStatus, Status de um pokemon
     */
    private function calculateValue(array $pokemonStats): string
    {
        $allStatsValues = array_map(fn ($stat) => $stat->base_stat, $pokemonStats);

        $statsValue = array_sum($allStatsValues);
        $statsValue = (($statsValue * (int)$this->increaseValue) / 100) + $statsValue;

        return (string)$statsValue;
    }
}
