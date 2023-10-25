<?php

namespace App\Http\Services;

use App\Http\External\ClientPokeApi\ClientPokeApi;
use App\Http\External\ClientPokeApi\ClientPokeApiDto;
use App\Models\Pokemon\Pokemon;
use App\Models\Pokemon\PokemonRelations\Ability;
use App\Models\Pokemon\PokemonRelations\PokemonAbility;
use App\Models\Pokemon\PokemonRelations\PokemonStatus;
use App\Models\Pokemon\PokemonRelations\PokemonType;
use Exception;
use Illuminate\Support\Facades\DB;

class PokemonService
{
    private string
        $uri;

    private int
        $numberOfExistingPokemons;

    public function __construct(
        private Pokemon $pokemon,

        private PokemonType $pokemonType,
        private PokemonStatus $pokemonStatus,
        private PokemonAbility $pokemonAbility,

        private ClientPokeApi $client,
        private ClientPokeApiDto $clientDto,
    ) {

        $this->uri = env("POKE_API_URI");

        $this->numberOfExistingPokemons = env("POKE_API_QUANTITY");
    }

    /**
     * Busca o pokemon pelo ID no banco, caso não exista é realizada a requisição para o serviço da
     * PokeApi e retornado ao cliente.
     */
    public function pokemonById(int $pokemonId)
    {
        if ($pokemonId > $this->numberOfExistingPokemons || $pokemonId < 1)
            throw new Exception("Pokemon ID inexistente", 400);

        // TODO Realizar a busca com o banco de dados antes de usar a API, banco incompleto ainda!

        $pokemonData = $this->requestExternalService($pokemonId);

        // Salva um novo pokemon no banco
        $this->handleSavePokemonInDatabase($pokemonData);
        return $pokemonData;
    }

    private function handleSavePokemonInDatabase(array $pokemonData): bool
    {
        if ($this->verifyPokemonData($pokemonData) == false)
            throw new Exception("Dados para criação de um pokemon inválidos.", 500);

        try {

            DB::beginTransaction();

            // $this->pokemonType->create($pokemonData);
            // $this->pokemonStatus->create($pokemonData);
            // $this->pokemonAbility->create($pokemonData);

            $this->pokemon->create($pokemonData);

            // DB::commit();

            return true;
        } catch (Exception $error) {

            DB::rollBack();
            throw $error;
        }
    }

    /**
     * Verifica a existência das chaves usadas para compor um pokemon no banco
     * com suas habilidades, status e seu/seus tipos.
     *
     * @param array $pokemonData,   Dados de um pokemon vindos da api PokeApi
     * @return bool,                Resultado da verificação das chaves
     */
    private function verifyPokemonData(array $pokemonData): bool
    {
        return match ($pokemonData) {
            !$pokemonData["stats"] => false,
            !$pokemonData["type"]  => false,
            !$pokemonData["abilities"] => false,

            default => true
        };
    }

    /**
     * Busca o pokemon pelo NOME no banco, caso não exista é realizada a requisição para o serviço da
     * PokeApi e retornado ao cliente.
     */
    public function pokemonByName(string $pokemonName)
    {
        $pokemon =  $this->requestExternalService($pokemonName);
    }

    /**
     * Monta a URL de busca com base no ID ou NOME de um Pokemon e realiza a requisição na API
     * da PokeApi
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

        echo '<pre>';
        print_r($pokemon);
        echo '</pre>';
        exit;
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
