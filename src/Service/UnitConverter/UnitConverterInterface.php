<?php

declare(strict_types=1);

namespace App\Service\UnitConverter;

use App\Enum\EdibleUnit;

interface UnitConverterInterface
{
    /**
     * @param float $fromValue
     * @param EdibleUnit $fromUnit
     * @param EdibleUnit $toUnit
     *
     * @return float
     */
    public function convert(float $fromValue, EdibleUnit $fromUnit, EdibleUnit $toUnit): float;
}