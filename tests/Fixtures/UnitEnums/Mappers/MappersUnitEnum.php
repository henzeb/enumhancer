<?php

namespace Henzeb\Enumhancer\Tests\Fixtures\UnitEnums\Mappers;

use Henzeb\Enumhancer\Concerns\Mappers;

enum MappersUnitEnum
{
    use Mappers;

    case Hearts;
    case Diamonds;
    case Clubs;
    case Spades;

    private const Love = self::Hearts;

    private const Golf = 'Clubs';

    private const Shovel = 3;

    private const MAP = [
        'Hearts' => 'Diamonds'
    ];

    private const MAP_FLIP = [
        'Spades' => 'Clubs'
    ];
}
