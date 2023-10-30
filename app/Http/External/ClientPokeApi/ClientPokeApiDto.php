<?php

namespace App\Http\External\ClientPokeApi;

use App\Models\Pokemon\Pokemon;
use Exception;

class ClientPokeApiDto
{
    public function __construct(
        private Pokemon $pokemonModelObject
    ) {
    }

    /**
     * Padroniza os dados retornados pela API e extrai os dados relevantes
     *
     * @param string $pokemonData, Dados de um pokemon em json
     */
    public function padronize(string $pokemonData): array
    {
        $pokemonData = json_decode($pokemonData, true);
        if (empty($pokemonData)) {
            throw new Exception("Dados do pokemon inexistentes", 400);
        }

        $pokemonData = $this->extractRelevantData($pokemonData);
        return $pokemonData;
    }

    /**
     * Padroniza os dados retornados pela API e extrai os dados relevantes com mais de um "dado"
     *
     * @param array $pokemonData, Dados de mais de um pokemon
     */
    public function padronizeManyData(array $pokemonData)
    {
    }

    /**
     * Extrai os dados relevantes do amontoado de dados retornados pela API
     *
     * @link https://pokeapi.co/
     *
     * @param array $pokemonData, Array contendo os dados de um pokemon
     */
    private function extractRelevantData(array $pokemonData): array
    {
        return [
            ...$this->extractPokemonData($pokemonData),
            "stats" => $this->extractPokemonStats($pokemonData),
            "type" => $this->extractPokemonType($pokemonData),
            "abilities" => $this->extractPokemonAbilities($pokemonData)
        ];
    }

    private function extractPokemonData(array $pokemonData): array
    {
        return [
            "pokemon_id" => $pokemonData["id"],
            "pokemon_name" => $pokemonData["name"],
            "base_experience" => $pokemonData["base_experience"],
            "height" => $pokemonData["height"],
            "weight" => $pokemonData["weight"]
        ];
    }

    private function extractPokemonStats(array $pokemonData): array
    {
        $pokemonStats = $pokemonData["stats"];
        $pokemonStats = array_map(function (array $pokemonStat) {

            $baseStat = $pokemonStat["base_stat"];
            return [
                "name" => $pokemonStat["stat"]["name"],
                "value" => $baseStat
            ];
        }, $pokemonStats);

        return $pokemonStats;
    }

    private function extractPokemonType(array $pokemonData): array
    {
        $pokemonTypes = $pokemonData["types"];
        $pokemonTypes = array_map(function (array $pokemonType) {

            return $pokemonType["type"]["name"];
        }, $pokemonTypes);

        return $pokemonTypes;
    }

    private function extractPokemonAbilities(array $pokemonData): array
    {
        $pokemonAbilities = $pokemonData["abilities"];
        $pokemonAbilities = array_map(function (array $pokemonAbility) {

            $isHidden = $pokemonAbility["is_hidden"];
            return [
                "name" => $pokemonAbility["ability"]["name"],
                "is_hidden" => $isHidden
            ];
        }, $pokemonAbilities);

        return $pokemonAbilities;
    }
}
