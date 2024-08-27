<?php

declare(strict_types=1);

namespace App\Tests\unit\App\Service\EdibleFormatter;

use App\Enum\EdibleType;
use App\Enum\EdibleUnit;
use App\Model\Edible;
use App\Service\EdibleFormatter\EdibleFormatter;
use App\Service\UnitConverter\UnitConverterInterface;
use PHPUnit\Framework\TestCase;

class EdibleFormatterTest extends TestCase
{
    public function testFormat(): void
    {
        // Arrange
        $testQuantity = 100.0;
        $fromUnit = EdibleUnit::GRAM;
        $toUnit = EdibleUnit::KILOGRAM;
        $edible = new Edible('Apple', EdibleType::FRUIT, $testQuantity, $fromUnit, 1);

        $unitConverterMock = $this->createMock(UnitConverterInterface::class);
        $unitConverterMock->method('convert')->with($testQuantity, $fromUnit, $toUnit)->willReturn(0.1);

        // Act
        $formatter = new EdibleFormatter($unitConverterMock);
        $result = $formatter->format($edible, EdibleUnit::KILOGRAM);

        // Assert
        $this->assertSame([
            'id' => 1,
            'type' => 'fruit',
            'name' => 'Apple',
            'quantity' => 0.1,
            'unit' => EdibleUnit::KILOGRAM->value,
        ], $result);
    }
}