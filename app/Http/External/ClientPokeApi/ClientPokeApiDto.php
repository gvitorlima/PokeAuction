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
        echo '<pre>';
        print_r($pokemonData);
        echo '</pre>';
        exit;
        if (empty($pokemonData)) {
            throw new Exception("Dados do pokemon inexistentes", 400);
        }

        $pokemonData = $this->extractRelevantData($pokemonData);
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
     * @param string $pokemonData, Json contendo os dados de um pokemon
     */
    private function extractRelevantData(array $pokemonData)
    {
    }
}
