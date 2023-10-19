<?php

namespace App\Http\Services;

use App\Http\External\ClientPokeApi\ClientPokeApi;
use App\Http\External\ClientPokeApi\ClientPokeApiDto;

use Exception;

class AuctionService
{
    private string
        $uri;

    private int
        $numberOfPokemonsForAuction,
        $numberOfExistingPokemons;

    public function __construct(
        private PokemonService $pokemonService
    ) {
        $this->uri = env("POKE_API_URI");

        $this->numberOfPokemonsForAuction = env("DAILY_QUANTITY");
        $this->numberOfExistingPokemons   = env("POKE_API_QUANTITY");
    }

    public function dailyAuction()
    {
    }

    /**
     * Retorna os pokemons para o leilão diário
     */
    public function dailyPokemons(): array
    {
        try {

            // TODO Criar um handle que realiza o processo abaixo, e verificar se tem os pokemons em cache já.
            $pokemonsId = $this->randomizePokemonsId();
            $pokemonsId = $this->handleCheckDataValidity($pokemonsId);

            $pokemons = array_map(function (int $id) {
                $pokemon = $this->pokemonService->pokemonById($id);

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
     * Função recursiva que verifica um ID repetido e substitute por um novo
     *
     * @param array $pokemonsId, ID's a serem verificados
     */
    private function handleCheckDataValidity(array $pokemonsId): array
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
}
