<?php

declare(strict_types=1);

namespace App\Repository\EdibleRepository;

use App\Filters\EdibleFilter;
use App\Model\Edible;
use App\Repository\Exception\InvalidEdibleIdException;

/**
 * Interface EdibleRepositoryInterface.
 *
 * @package App\Repository\EdibleRepository
 */
interface EdibleRepositoryInterface
{
    /**
     * @param Edible $edible
     *
     * @return void
     *
     * @throws InvalidEdibleIdException
     */
    public function add(Edible $edible): void;

    /**
     * @param int $id
     *
     * @return void
     */
    public function remove(int $id): void;

    /**
     * @param EdibleFilter|null $filter
     *
     * @return Edible[]
     */
    public function list(?EdibleFilter $filter): array;
}