<?php

namespace App\Http\Services;

use App\Http\External\ClientPokeApi\ClientPokeApi;
use App\Http\External\ClientPokeApi\ClientPokeApiDto;

use App\Enums\PokemonType as EnumPokemonType;
use App\Enums\PokemonStatus as EnumPokemonStatus;
use App\Http\Requests\SearchPokemonRequest;
use App\Models\Pokemon\Pokemon;
use App\Models\Pokemon\PokemonRelations\Ability;
use App\Models\Pokemon\PokemonRelations\PokemonAbility;
use App\Models\Pokemon\PokemonRelations\PokemonStatus;
use App\Models\Pokemon\PokemonRelations\PokemonType;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PokemonService
{
    private string
        $uri;

    private int
        $numberOfExistingPokemons;

    public function __construct(
        private Pokemon $modelPokemon,

        private Ability $modelAbility,

        private PokemonType     $modelPokemonType,
        private PokemonStatus   $modelPokemonStatus,
        private PokemonAbility  $modelPokemonAbility,

        private ClientPokeApi       $clientPokeApi,
        private ClientPokeApiDto    $clientPokeApiDto,
    ) {

        $this->uri = env("POKE_API_URI");

        $this->numberOfExistingPokemons = env("POKE_API_QUANTITY");
    }

    /**
     * Busca o pokemon pelo ID no banco, caso não exista é realizada a requisição para o serviço da
     * PokeApi e retornado ao cliente.
     * @param int $pokemonId,   Identificador do respectivo Pokemon
     */
    public function pokemonById(int $pokemonId)
    {
        $this->validatorPokemonId($pokemonId);

        $pokemonData = $this->requestExternalService($pokemonId);
        $this->handleSavePokemonInDatabase($pokemonData);

        return $pokemonData;
    }

    /**
     * Busca por vários Pokemons presentes no array informado
     * @param array $pokemonIds,    Array de ID's dos pokemons sendo buscados
     */
    public function pokemonByIds(array $pokemonIds)
    {
        $this->validatorPokemonId($pokemonIds);
    }

    /**
     * Busca o pokemon pelo NOME no banco, caso não exista é realizada a requisição para o serviço da
     * PokeApi e retornado ao cliente.
     * @param string $pokemonName,  Nome do pokemon a ser buscado
     */
    public function pokemonByName(string $pokemonName)
    {
        $pokemon =  $this->requestExternalService($pokemonName);
    }

    /**
     * Valida se os IDS batem com as condições do projeto/API usada na requisição
     */
    private function validatorPokemonId(int|array $pokemonField): void
    {
        if (is_int($pokemonField)) {
            $validator = Validator::make(["id" => $pokemonField], [
                "id" => "int|between:1," . $this->numberOfExistingPokemons
            ]);
        } else {
            $validator = Validator::make($pokemonField, [
                "*.pokemonsId" => "int|between:1," . $this->numberOfExistingPokemons
            ]);
        }

        if ($validator->fails()) {
            throw new Exception("Identificador não corresponde com o esperado.", 400);
        }
    }

    /**
     * Função handle que delega a invocação das models para a inserção de um novo pokemon no banco de dados
     *
     * @param array $pokemonData,   Dados de um pokemon já padronizados
     */
    private function handleSavePokemonInDatabase(array $pokemonData): void
    {
        try {

            DB::beginTransaction();

            $pokemonObject = $this->createPokemon($pokemonData);

            $this->composePokemonWithStatus($pokemonData);
            $this->composePokemonWithAbilities($pokemonObject, $pokemonData['abilities']);
            $this->composePokemonWithTypes($pokemonObject, $pokemonData['type']);

            DB::commit();
        } catch (Exception $error) {
            DB::rollBack();
            throw $error;
        }
    }

    /**
     * Cria um novo Pokemon no banco com os dados já formatados vindos do DTO
     * @param array $pokemonData,   Array contendo os dados de um novo pokemon
     */
    private function createPokemon(array $pokemonData): Pokemon
    {
        $pokemonObject = $this->modelPokemon::create($pokemonData);
        return $pokemonObject;
    }

    /**
     * Método que compõe as tabelas vinculadas a um Pokemon
     * @param Pokemon,  Um objeto de Pokemon
     * @param array,    Array contendo os typos do respectivo pokemon
     */
    private function composePokemonWithTypes(Pokemon $pokemon, array $pokemonTypes): void
    {
        foreach ($pokemonTypes as $type) {

            $type = EnumPokemonType::tryFromName($type);
            if (empty($type))
                continue;

            $this->modelPokemonType::create([
                "pokemon_id" => $pokemon->pokemon_id,
                "type_id" => $type->value
            ]);
        }
    }

    /**
     * Método que compõe um pokemon com seus status (atk, def...)
     * @param array $pokemonData,   Dados já formatados retornados de um pokemon
     */
    private function composePokemonWithStatus(array $pokemonData): void
    {
        $pokemonData["specialAttack"]  = $pokemonData["special-attack"];
        $pokemonData["specialDefense"] = $pokemonData["special-defense"];

        $this->modelPokemonStatus::create($pokemonData);
    }

    /**
     * Compõe um Pokemon com suas Habilidades
     * @param Pokemon $pokemon,         Objeto de um Pokemon
     * @param array $pokemonAbilities,  Array contendo as habilidades de um pokemon
     */
    private function composePokemonWithAbilities(Pokemon $pokemon, array $pokemonAbilities): void
    {
        foreach ($pokemonAbilities as $ability) {

            $pokemonAbility = $this->modelAbility->replicate();
            $pokemonAbility = $pokemonAbility->getByName($ability["name"])
                ->get()->first();

            if (empty($pokemonAbility)) {
                $pokemonAbility = $this->modelAbility->replicate()->fill($ability);
                $pokemonAbility->save();
            }

            $this->modelPokemonAbility::create([
                "pokemon_id" => $pokemon->pokemon_id,
                "ability_id" => $pokemonAbility->id
            ]);
        }
    }

    /**
     * Monta a URL de busca com base no ID ou NOME de um Pokemon e realiza a requisição na API
     * da PokeApi
     *
     * @param int|string $searchParameter,  Parâmetro de busca associado a um pokemon podendo
     *                                      ser seu NOME ou ID
     */
    private function requestExternalService(int|string $searchParameter): array
    {
        $pokemon = $this->clientPokeApi->sendRequest($this->mountPokemonUrlById($searchParameter), "GET");
        $pokemon = $this->clientPokeApiDto->padronize(
            $pokemon->getBody()
                ->getContents()
        );

        return $pokemon;
    }

    /**
     * Concatena a URL de pesquisa com o ID ou NOME do pokemon
     *
     * @param int|string $searchParam, ID ou NOME do pokemon
     */
    private function mountPokemonUrlById(int|string $searchParam): string
    {
        return $this->uri . "/$searchParam";
    }
}
