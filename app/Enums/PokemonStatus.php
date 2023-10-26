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
    public static function seed(): array
    {
        return [
            ['id' => 1, 'name' => self::hp->value],
            ['id' => 2, 'name' => self::attack->value],
            ['id' => 3, 'name' => self::defense->value],
            ['id' => 4, 'name' => self::speed->value],
            ['id' => 5, 'name' => self::specialAttack->value],
            ['id' => 6, 'name' => self::specialDefense->value],
        ];
    }
};
