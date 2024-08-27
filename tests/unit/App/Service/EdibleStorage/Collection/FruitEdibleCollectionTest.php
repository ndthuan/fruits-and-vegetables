<?php

declare(strict_types=1);

// Test cases
namespace App\Tests\unit\App\Service\EdibleStorage\Collection;

use App\Filters\EdibleFilter;
use App\Model\Edible;
use App\Repository\EdibleRepository\EdibleRepositoryInterface;
use App\Service\EdibleStorage\Collection\FruitCollection;
use App\Service\EdibleStorage\Exception\InvalidEdibleTypeException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use App\Enum\EdibleType;
use App\Enum\EdibleUnit;

class FruitEdibleCollectionTest extends TestCase
{
    /**
     * @var EdibleRepositoryInterface|MockObject
     */
    private $repositoryMock;

    /**
     * @var FruitCollection
     */
    private FruitCollection $fruitCollection;

    protected function setUp(): void
    {
        $this->repositoryMock = $this->createMock(EdibleRepositoryInterface::class);
        $this->fruitCollection = new FruitCollection($this->repositoryMock);
    }

    public function testAddValidEdible()
    {
        $edible = new Edible('Apple', EdibleType::FRUIT, 100, EdibleUnit::GRAM, 1);

        $this->repositoryMock->expects($this->once())
            ->method('add')
            ->with($edible);

        $this->fruitCollection->add($edible);
    }

    public function testAddInvalidEdibleShouldThrowException()
    {
        $this->expectException(InvalidEdibleTypeException::class);

        $edible = new Edible('Carrot', EdibleType::VEGETABLE, 100, EdibleUnit::GRAM, 1);
        $this->fruitCollection->add($edible);
    }

    public function testRemoveEdible()
    {
        $this->repositoryMock->expects($this->once())
            ->method('remove')
            ->with(1);

        $this->fruitCollection->remove(1);
    }

    public function testListEdibles()
    {
        $expectedList = [
            new Edible('Apple', EdibleType::FRUIT, 100, EdibleUnit::GRAM, 1),
            new Edible('Banana', EdibleType::FRUIT, 150, EdibleUnit::GRAM, 2)
        ];

        $this->repositoryMock->expects($this->once())
            ->method('list')
            ->with(null)
            ->willReturn($expectedList);

        $result = $this->fruitCollection->list(null);

        $this->assertEquals($expectedList, $result);
    }

    public function testListEdiblesWithKeyword()
    {
        $expectedList = [
            new Edible('Apple', EdibleType::FRUIT, 100, EdibleUnit::GRAM, 1)
        ];

        $filter = new EdibleFilter('Apple');

        $this->repositoryMock->expects($this->once())
            ->method('list')
            ->with($filter)
            ->willReturn($expectedList);

        $result = $this->fruitCollection->list($filter);

        $this->assertEquals($expectedList, $result);
    }
}