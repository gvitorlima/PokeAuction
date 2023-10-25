<?php

namespace App\Enums;

enum PokemonStatus: string
{
    case hp = 'hp';
    case attack = 'attack';
    case defense = 'defense';
    case speed = 'speed';
    case specialAttack = 'special-attack';
    case specialDefense = 'special-defense';

    /**
     * Retorna os valores do Enum já formatados para somente usar em uma Seed.
     *
     * @return array,   array contendo os arrays já formatados.
     */
    public static function seedStatus(): array
    {
        return [
            ['id' => 1, 'name' => self::hp],
            ['id' => 2, 'name' => self::attack],
            ['id' => 3, 'name' => self::defense],
            ['id' => 4, 'name' => self::speed],
            ['id' => 5, 'name' => self::specialAttack],
            ['id' => 6, 'name' => self::specialDefense],
        ];
    }
};
