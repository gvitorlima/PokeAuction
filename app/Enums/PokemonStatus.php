<?php

namespace App\Enums;

enum PokemonStatus: int
{
    case hp = 1;
    case attack = 2;
    case defense = 3;
    case speed = 4;
    case specialAttack = 5;
    case specialDefense = 6;

    public static function tryFromName(string $name): self|array
    {
        return match ($name) {
            self::hp->name      => self::hp,
            self::attack->name  => self::attack,
            self::defense->name => self::defense,
            self::speed->name   => self::speed,
            self::specialAttack->name  => self::specialAttack,
            self::specialDefense->name => self::specialDefense,

            default => []
        };
    }

    /**
     * Retorna os valores do Enum já formatados para somente usar em uma Seed.
     *
     * @return array,   array contendo os arrays já formatados.
     */
    public static function seed(): array
    {
        return [
            ['id' => self::hp->value, 'name' => self::hp->name],
            ['id' => self::attack->value, 'name' => self::attack->name],
            ['id' => self::defense->value, 'name' => self::defense->name],
            ['id' => self::speed->value, 'name' => self::speed->name],
            ['id' => self::specialAttack->value, 'name' => self::specialAttack->name],
            ['id' => self::specialDefense->value, 'name' => self::specialDefense->name],
        ];
    }
};
