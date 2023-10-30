<?php

namespace App\Http\Services;

use App\Http\External\ClientPokeApi\ClientPokeApi;
use App\Http\External\ClientPokeApi\ClientPokeApiDto;

use App\Enums\PokemonType as EnumPokemonType;
use App\Enums\PokemonStatus as EnumPokemonStatus;

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
        private Pokemon $modelPokemon,

        private ability $modelAbility,

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
     */
    public function pokemonById(int $pokemonId)
    {
        if ($pokemonId > $this->numberOfExistingPokemons || $pokemonId < 1)
            throw new Exception("Pokemon ID inexistente", 400);

        // TODO Realizar a busca com o banco de dados antes de usar a API, banco incompleto ainda!

        $pokemon = $this->requestExternalService($pokemonId);

        // Salva um novo pokemon no banco
        $this->handleSavePokemonInDatabase($pokemon);
        return $pokemon;
    }

    /**
     * Handle que delega a invocação das models para a inserção de um novo pokemon no banco de dados
     *
     * @param array $pokemon,   Dados de um pokemon já padronizados
     */
    private function handleSavePokemonInDatabase(array $pokemon): bool
    {
        try {

            $newPokemon = $this->modelPokemon::create($pokemon);

            $this->aggregatePokemonTypes($newPokemon, $pokemon['type']);
            $this->aggregatePokemonStatus($newPokemon, $pokemon['stats']);
            $this->aggregatePokemonAbilities($newPokemon, $pokemon['abilities']);

            return true;
        } catch (Exception $error) {

            DB::rollBack();
            echo '<pre>';
            print_r($error->getMessage());
            echo '</pre>';
            exit;
            throw $error;
        }
    }

    /**
     * Função contendo a lógica de agregação de valores/relacionamentos dos tipos de um pokemon especifico
     *
     * @param array $pokemonTypes   , Array contendo os tipos do pokemon especifico
     */
    private function aggregatePokemonTypes(Pokemon $pokemon, array $pokemonTypes): void
    {
        foreach ($pokemonTypes as $type) {

            $type = EnumPokemonType::tryFromName($type);
            if (empty($type))
                continue;

            $this->modelPokemonType::created([...$pokemon->toArray(), "type_id" => $type->value]);
        }
    }

    /**
     * Função contendo a lógica de agregação de valores/relacionamentos dos status de um pokemon especifico
     *
     * @param array $pokemonStatus  , Array contendo os status do pokemon especifico
     */
    private function aggregatePokemonStatus(Pokemon $pokemon, array $pokemonStatus): void
    {
        foreach ($pokemonStatus as $status) {

            $enumStatus = EnumPokemonStatus::tryFromName($status["name"]);
            if (empty($enumStatus))
                continue;

            $this->modelPokemonStatus::create([
                ...$pokemon->toArray(),
                "status_id" => $enumStatus->value,
                "value" => $status["value"]
            ]);
        }
    }

    /**
     * Função contendo a lógica de agregação de valores/relacionamentos das habilidades de um pokemon especifico
     *
     * @param array $pokemonAbilities   , Array contendo as habilidades do pokemon especifico
     */
    private function aggregatePokemonAbilities(Pokemon $pokemon, array $pokemonAbilities): void
    {
        // Habilidades não possui Enum pois existem muitas.
        foreach ($pokemonAbilities as $ability) {

            $pokemonAbility = $this->modelAbility->getByName($ability["name"]);
            $pokemonAbility = $pokemonAbility->get()->toArray();

            $ability = empty($pokemonAbility) ?
                $this->createNewAbility($ability["name"]) :
                $pokemonAbility;

            $this->modelPokemonAbility->created([
                ...$pokemon->toArray(),
                "ability_id" => $ability->id
            ]);
        }
    }

    private function createNewAbility(string $name): Ability
    {
        $this->modelAbility->createAbility($name);
        $this->modelAbility->save();

        return $this->modelAbility;
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
     * @param int $searchParam, ID do pokemon
     */
    private function mountPokemonUrlById(int|string $searchParam): string
    {
        return $this->uri . "/$searchParam";
    }
}
