<?php

declare(strict_types=1);

namespace App\Service\EdibleStorage\Collection;

use App\Filters\EdibleFilter;
use App\Model\Edible;
use App\Repository\EdibleRepository\EdibleRepositoryInterface;
use App\Service\EdibleStorage\Exception\InvalidEdibleTypeException;

/**
 * Class AbstractEdibleCollection.
 *
 * @package App\Service\EdibleStorage
 */
abstract class AbstractEdibleCollection implements EdibleCollectionInterface
{
    /**
     * AbstractEdibleCollection constructor.
     *
     * @param EdibleRepositoryInterface $repository
     */
    public function __construct(private readonly EdibleRepositoryInterface $repository){}

    /**
     * @inheritDoc
     */
    public function add(Edible $edible): void
    {
        if ($edible->getType() !== $this->getAcceptableType()) {
            throw new InvalidEdibleTypeException(sprintf(
                'Invalid edible type "%s" provided, expected "%s"',
                $edible->getType()->value,
                $this->getAcceptableType()->value,
            ));
        }

        $this->repository->add($edible);
    }

    /**
     * @inheritDoc
     */
    public function remove(int $id): void
    {
        $this->repository->remove($id);
    }

    /**
     * @inheritDoc
     */
    public function list(?EdibleFilter $filter): array
    {
        return $this->repository->list($filter);
    }
}