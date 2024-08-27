<?php

declare(strict_types=1);

namespace App\Tests\unit\App\Repository\EdibleRepository;

use App\Enum\EdibleType;
use App\Enum\EdibleUnit;
use App\Filters\EdibleFilter;
use App\Model\Edible;
use App\Repository\EdibleRepository\InMemoryEdibleRepository;
use App\Repository\Exception\InvalidEdibleIdException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class InMemoryEdibleRepositoryTest extends TestCase
{
    private InMemoryEdibleRepository $repository;

    protected function setUp(): void
    {
        $this->repository = new InMemoryEdibleRepository([
            1 => new Edible('AAA', EdibleType::FRUIT, 100, EdibleUnit::GRAM, 1000),
            2 => new Edible('BBB', EdibleType::FRUIT, 200, EdibleUnit::GRAM, 2000),
        ]);
    }

    #[DataProvider('listDataProvider')]
    public function testList(?EdibleFilter $filter, int $expectedCount): void
    {
        $results = $this->repository->list($filter);
        $this->assertCount($expectedCount, $results);

        if ($expectedCount > 0 && $filter) {
            $matchFound = false;

            foreach ($results as $result) {
                if ($filter->matches($result)) {
                    $matchFound = true;
                    break;
                }
            }

            $this->assertTrue($matchFound, 'No match found');
        }
    }

    #[DataProvider('addDataProvider')]
    public function testAdd(?int $testId, int $expectedCount, ?string $expectedException): void
    {
        $edible = $this->createMock(Edible::class);
        $edible->method('getId')->willReturn($testId);

        if ($expectedException) {
            $this->expectException($expectedException);
        }

        $this->repository->add($edible);

        $this->assertCount($expectedCount, $this->repository->list(null));
    }

    #[DataProvider('removeDataProvider')]
    #
    public function testRemove(int $testId, int $expectedCount): void
    {
        $this->repository->remove($testId);

        $this->assertCount($expectedCount, $this->repository->list(null));
    }

    public static function addDataProvider(): array
    {
        return [
            'null id' => ['testId' => null, 'expectedCount' => 2, 'expectedException' => InvalidEdibleIdException::class],
            'new id' => ['testId' => 3, 'expectedCount' => 3, 'expectedException' => null],
            'duplicate id' => ['testId' => 2, 'expectedCount' => 2, 'expectedException' => null],
        ];
    }

    public static function removeDataProvider(): array
    {
        return [
            'existing id' => ['testId' => 1, 'expectedCount' => 1],
            'non-existing id' => ['testId' => 3, 'expectedCount' => 2],
        ];
    }

    public static function listDataProvider(): array
    {
        return [
            'no keyword' => ['filter' => new EdibleFilter(), 'expectedCount' => 2],
            'matching keyword' => ['filter' => new EdibleFilter('aaa'), 'expectedCount' => 1],
            'non-matching keyword' => ['filter' => new EdibleFilter('ccc'), 'expectedCount' => 0],
            'matching minGram' => ['filter' => new EdibleFilter(null, 150), 'expectedCount' => 1],
            'non-matching minGram' => ['filter' => new EdibleFilter(null, 250), 'expectedCount' => 0],
            'matching maxGram' => ['filter' => new EdibleFilter(null, null, 150), 'expectedCount' => 1],
            'non-matching maxGram' => ['filter' => new EdibleFilter(null, null, 50), 'expectedCount' => 0],
        ];
    }
}