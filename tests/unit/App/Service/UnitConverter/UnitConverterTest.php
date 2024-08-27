<?php

declare(strict_types=1);

namespace App\Tests\unit\App\Service\UnitConverter;

use App\Enum\EdibleUnit;
use App\Service\UnitConverter\UnitConverter;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class UnitConverterTest extends TestCase
{
    #[DataProvider('convertDataProvider')]
    public function testConvert(float $fromValue, EdibleUnit $fromUnit, EdibleUnit $toUnit, float $expectedResult): void
    {
        $unitConverter = new UnitConverter();

        $actualResult = $unitConverter->convert($fromValue, $fromUnit, $toUnit);

        $this->assertEquals($expectedResult, $actualResult);
    }

    public static function convertDataProvider(): array
    {
        return [
            'same unit: gram' => ['fromValue' => 100, 'fromUnit' => EdibleUnit::GRAM, 'toUnit' => EdibleUnit::GRAM, 'expectedResult' => 100],
            'same unit: kilogram' => ['fromValue' => 1, 'fromUnit' => EdibleUnit::KILOGRAM, 'toUnit' => EdibleUnit::KILOGRAM, 'expectedResult' => 1],
            'gram to kilogram' => ['fromValue' => 1000, 'fromUnit' => EdibleUnit::GRAM, 'toUnit' => EdibleUnit::KILOGRAM, 'expectedResult' => 1],
            'kilogram to gram' => ['fromValue' => 1, 'fromUnit' => EdibleUnit::KILOGRAM, 'toUnit' => EdibleUnit::GRAM, 'expectedResult' => 1000],
        ];
    }
}