<?php

declare(strict_types=1);

// Test cases
namespace App\Tests\unit\App\Service;

use App\Enum\EdibleType;
use App\Filters\EdibleFilter;
use App\Model\Edible;
use App\Service\EdibleStorage\Collection\EdibleCollectionInterface;
use App\Service\EdibleStorage\Collection\FruitCollection;
use App\Service\EdibleStorage\Collection\VegetableCollection;
use App\Service\EdibleStorage\EdibleStorageService;
use App\Service\EdibleStorage\Exception\DuplicateEdibleException;
use App\Service\EdibleStorage\Exception\EdibleNotFoundException;
use App\Service\EdibleStorage\Exception\InvalidEdibleTypeException;
use App\Service\EdibleStorage\RequestReaderInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use App\Enum\EdibleUnit;
use ReflectionClass;
use ReflectionEnum;

class EdibleStorageServiceTest extends TestCase
{
    /** @var RequestReaderInterface|MockObject */
    private $requestReaderMock;

    /** @var FruitCollection|MockObject */
    private $fruitCollectionMock;

    /** @var VegetableCollection|MockObject */
    private $vegetableCollectionMock;

    /** @var EdibleStorageService|MockObject */
    private $edibleStorageService;

    protected function setUp(): void
    {
        $this->requestReaderMock = $this->createMock(RequestReaderInterface::class);
        $this->fruitCollectionMock = $this->createMock(EdibleCollectionInterface::class);
        $this->vegetableCollectionMock = $this->createMock(EdibleCollectionInterface::class);

        $this->fruitCollectionMock->method('getAcceptableType')->willReturn(EdibleType::FRUIT);
        $this->vegetableCollectionMock->method('getAcceptableType')->willReturn(EdibleType::VEGETABLE);

        $collections = [
            $this->fruitCollectionMock,
            $this->vegetableCollectionMock,
        ];

        $this->edibleStorageService = new EdibleStorageService($this->requestReaderMock, $collections);
    }

    public function testAddValidEdible()
    {
        $edible = new Edible('Apple', EdibleType::FRUIT, 100, EdibleUnit::GRAM);

        $this->fruitCollectionMock->expects($this->once())
            ->method('add')
            ->with($edible);

        $this->edibleStorageService->add($edible);
    }

    public function testAddDuplicateEdible()
    {
        $this->expectException(DuplicateEdibleException::class);

        $edible = new Edible('Apple', EdibleType::FRUIT, 100, EdibleUnit::GRAM);
        $edible->setId(1);

        $this->edibleStorageService->add($edible);
        $this->edibleStorageService->add($edible);
    }

    public function testRemoveEdible()
    {
        $edible = new Edible('Apple', EdibleType::FRUIT, 100, EdibleUnit::GRAM);
        $edible->setId(1);

        $this->edibleStorageService->add($edible);

        $this->fruitCollectionMock->expects($this->once())
            ->method('remove')
            ->with(1);

        $this->edibleStorageService->remove(1);
    }

    public function testRemoveNonExistentEdible()
    {
        $this->expectException(EdibleNotFoundException::class);

        $this->edibleStorageService->remove(999);
    }

    public function testListEdibles()
    {
        $expectedList = [
            new Edible('Apple', EdibleType::FRUIT, 100, EdibleUnit::GRAM),
            new Edible('Banana', EdibleType::FRUIT, 150, EdibleUnit::GRAM),
        ];

        $this->fruitCollectionMock->expects($this->once())
            ->method('list')
            ->with(null)
            ->willReturn($expectedList);

        $result = $this->edibleStorageService->list(null, null);

        $this->assertEquals($expectedList, $result);
    }

    public function testListEdiblesWithKeyword()
    {
        $expectedList = [
            new Edible('Apple', EdibleType::FRUIT, 100, EdibleUnit::GRAM),
        ];

        $filter = new EdibleFilter('Apple');

        $this->fruitCollectionMock->expects($this->once())
            ->method('list')
            ->with($filter)
            ->willReturn($expectedList);

        $result = $this->edibleStorageService->list(null, $filter);

        $this->assertEquals($expectedList, $result);
    }
}