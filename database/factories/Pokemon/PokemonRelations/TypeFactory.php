<?php

namespace Database\Factories\Pokemon\PokemonRelations;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class TypeFactory extends Factory
{
    public function definition(): array
    {
        return [
            'id' => 1,
            'name' => 'any name'
        ];
    }
}
