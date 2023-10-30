<?php

namespace App\Enums;

enum PokemonType: int
{
    case normal = 1;
    case fire = 2;
    case water = 3;
    case grass = 4;
    case electric = 5;
    case ice = 6;
    case fighting = 7;
    case poison = 8;
    case ground = 9;
    case flying = 10;
    case psychic = 11;
    case bug = 12;
    case rock = 13;
    case ghost = 14;
    case dark = 15;
    case steel = 16;
    case fairy = 17;
    case dragon = 18;

    public static function tryFromName(string $name): self|array
    {
        return match ($name) {
            self::normal->name   => self::normal,
            self::fire->name     => self::fire,
            self::water->name    => self::water,
            self::grass->name    => self::grass,
            self::electric->name => self::electric,
            self::ice->name      => self::ice,
            self::fighting->name => self::fighting,
            self::poison->name   => self::poison,
            self::ground->name   => self::ground,
            self::flying->name   => self::flying,
            self::psychic->name  => self::psychic,
            self::bug->name      => self::bug,
            self::rock->name     => self::rock,
            self::ghost->name    => self::ghost,
            self::dark->name     => self::dark,
            self::steel->name    => self::steel,
            self::fairy->name    => self::fairy,
            self::dragon->name   => self::dragon,

            default => []
        };
    }

    public static function seed(): array
    {
        return [
            ['id' => self::normal->value, 'name' => self::normal->name],
            ['id' => self::fire->value, 'name' => self::fire->name],
            ['id' => self::water->value, 'name' => self::water->name],
            ['id' => self::grass->value, 'name' => self::grass->name],
            ['id' => self::electric->value, 'name' => self::electric->name],
            ['id' => self::ice->value, 'name' => self::ice->name],
            ['id' => self::fighting->value, 'name' => self::fighting->name],
            ['id' => self::poison->value, 'name' => self::poison->name],
            ['id' => self::ground->value, 'name' => self::ground->name],
            ['id' => self::flying->value, 'name' => self::flying->name],
            ['id' => self::psychic->value, 'name' => self::psychic->name],
            ['id' => self::bug->value, 'name' => self::bug->name],
            ['id' => self::rock->value, 'name' => self::rock->name],
            ['id' => self::ghost->value, 'name' => self::ghost->name],
            ['id' => self::dark->value, 'name' => self::dark->name],
            ['id' => self::steel->value, 'name' => self::steel->name],
            ['id' => self::fairy->value, 'name' => self::fairy->name],
            ['id' => self::dragon->value, 'name' => self::dragon->name]
        ];
    }
};
