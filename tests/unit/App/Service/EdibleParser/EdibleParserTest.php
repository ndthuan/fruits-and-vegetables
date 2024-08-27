<?php

declare(strict_types=1);

namespace App\Tests\unit\App\Service\EdibleParser;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use App\Service\EdibleParser\EdibleParser;
use App\Service\UnitConverter\UnitConverterInterface;
use App\Enum\EdibleUnit;
use App\Enum\EdibleType;
use App\Model\Edible;

class EdibleParserTest extends TestCase
{
    /** @var UnitConverterInterface|MockObject */
    private $unitConverterMock;

    private EdibleParser $edibleParser;

    protected function setUp(): void
    {
        $this->unitConverterMock = $this->createMock(UnitConverterInterface::class);
        $this->edibleParser = new EdibleParser($this->unitConverterMock);
    }

    public function testParseEdibleWithValidGramUnit()
    {
        $item = [
            'id' => 1,
            'name' => 'Apple',
            'unit' => 'g',
            'quantity' => 100,
            'type' => 'fruit'
        ];

        $edible = $this->edibleParser->parseEdible($item);

        $this->assertInstanceOf(Edible::class, $edible);
        $this->assertEquals('Apple', $edible->getName());
        $this->assertEquals(EdibleType::FRUIT, $edible->getType());
        $this->assertEquals(100, $edible->getQuantity());
        $this->assertEquals(EdibleUnit::GRAM, $edible->getUnit());
        $this->assertEquals(1, $edible->getId());
    }

    public function testParseEdibleWithValidKilogramUnit()
    {
        $this->unitConverterMock->method('convert')
            ->willReturn(1000.0);

        $item = [
            'id' => 2,
            'name' => 'Carrot',
            'unit' => 'kg',
            'quantity' => 1,
            'type' => 'vegetable'
        ];

        $edible = $this->edibleParser->parseEdible($item);

        $this->assertInstanceOf(Edible::class, $edible);
        $this->assertEquals('Carrot', $edible->getName());
        $this->assertEquals(EdibleType::VEGETABLE, $edible->getType());
        $this->assertEquals(1000.0, $edible->getQuantity(), 'Unit should be converted to grams');
        $this->assertEquals(EdibleUnit::GRAM, $edible->getUnit());
        $this->assertEquals(2, $edible->getId());
    }

    public function testParseEdibleWithMissingFields()
    {
        $unitConverter = $this->createMock(UnitConverterInterface::class);
        $parser = new EdibleParser($unitConverter);

        $item = [
            'name' => 'Banana',
            'unit' => 'GRAM',
            'quantity' => 150
        ];

        $edible = $parser->parseEdible($item);

        $this->assertNull($edible);
    }

    public function testParseEdibleWithUnsupportedUnit()
    {
        $unitConverter = $this->createMock(UnitConverterInterface::class);
        $parser = new EdibleParser($unitConverter);

        $item = [
            'id' => 3,
            'name' => 'Orange',
            'unit' => 'LITER',
            'quantity' => 1,
            'type' => 'FRUIT'
        ];

        $edible = $parser->parseEdible($item);

        $this->assertNull($edible);
    }

    public function testParseEdibleWithUnsupportedType()
    {
        $unitConverter = $this->createMock(UnitConverterInterface::class);
        $parser = new EdibleParser($unitConverter);

        $item = [
            'id' => 4,
            'name' => 'Chicken',
            'unit' => 'GRAM',
            'quantity' => 500,
            'type' => 'MEAT'
        ];

        $edible = $parser->parseEdible($item);

        $this->assertNull($edible);
    }

}