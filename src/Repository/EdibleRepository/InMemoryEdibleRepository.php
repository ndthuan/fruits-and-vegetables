<?php

declare(strict_types=1);

namespace App\Repository\EdibleRepository;

use App\Filters\EdibleFilter;
use App\Model\Edible;
use App\Repository\Exception\InvalidEdibleIdException;

/**
 * Class InMemoryEdibleRepository.
 */
class InMemoryEdibleRepository implements EdibleRepositoryInterface
{
    /**
     * @param Edible[] $edibles
     */
    public function __construct(private array $edibles = [])
    {
    }

    /**
     * @inheritDoc
     */
    public function add(Edible $edible): void
    {
        if ($edible->getId() === null) {
            throw new InvalidEdibleIdException('Edible id cannot be null');
        }

        $this->edibles[$edible->getId()] = $edible;
    }

    /**
     * @inheritDoc
     */
    public function remove(int $id): void
    {
        unset($this->edibles[$id]);
    }

    /**
     * @inheritDoc
     */
    public function list(?EdibleFilter $filter): array
    {
        if ($filter === null) {
            return $this->edibles;
        }

        return array_filter($this->edibles, fn(Edible $edible) => $filter->matches($edible));
    }
}