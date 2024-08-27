<?php

declare(strict_types=1);

namespace App\Service\UnitConverter;

use App\Enum\EdibleUnit;

class UnitConverter implements UnitConverterInterface
{
    private const CONVERSION_RATES = [
        EdibleUnit::GRAM->value => 1,
        EdibleUnit::KILOGRAM->value => 1000,
    ];

    /**
     * @inheritDoc
     */
    public function convert(float $fromValue, EdibleUnit $fromUnit, EdibleUnit $toUnit): float
    {
        $baseValue = $fromValue * self::CONVERSION_RATES[$fromUnit->value];

        return $baseValue / self::CONVERSION_RATES[$toUnit->value];
    }
}