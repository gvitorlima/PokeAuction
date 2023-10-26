<?php

namespace App\Enums;

use Illuminate\Validation\Rules\Enum;

enum PokemonType: string
{
    case normal = 'normal';
    case fire = 'fire';
    case water = 'water';
    case grass = 'grass';
    case electric = 'electric';
    case ice = 'ice';
    case fighting = 'fighting';
    case poison = 'poison';
    case ground = 'ground';
    case flying = 'flying';
    case psychic = 'psychic';
    case bug = 'bug';
    case rock = 'rock';
    case ghost = 'ghost';
    case dark = 'dark';
    case steel = 'steel';
    case fairy = 'fairy';
    case dragon = 'dragon';

    public static function seed(): array
    {
        return [
            ['id' => 1, 'name' => self::normal->value],
            ['id' => 2, 'name' => self::fire->value],
            ['id' => 3, 'name' => self::water->value],
            ['id' => 4, 'name' => self::grass->value],
            ['id' => 5, 'name' => self::electric->value],
            ['id' => 6, 'name' => self::ice->value],
            ['id' => 7, 'name' => self::fighting->value],
            ['id' => 8, 'name' => self::poison->value],
            ['id' => 9, 'name' => self::ground->value],
            ['id' => 10, 'name' => self::flying->value],
            ['id' => 11, 'name' => self::psychic->value],
            ['id' => 12, 'name' => self::bug->value],
            ['id' => 13, 'name' => self::rock->value],
            ['id' => 14, 'name' => self::ghost->value],
            ['id' => 15, 'name' => self::dark->value],
            ['id' => 16, 'name' => self::steel->value],
            ['id' => 17, 'name' => self::fairy->value],
            ['id' => 18, 'name' => self::dragon->value]
        ];
    }
};
