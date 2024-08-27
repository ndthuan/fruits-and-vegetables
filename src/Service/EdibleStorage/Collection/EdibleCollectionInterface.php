<?php

declare(strict_types=1);

namespace App\Service\EdibleStorage\Collection;

use App\Enum\EdibleType;
use App\Filters\EdibleFilter;
use App\Model\Edible;
use App\Service\EdibleStorage\Exception\InvalidEdibleTypeException;

/**
 * Interface EdibleCollectionInterface.
 *
 * @package App\Service\EdibleStorage
 */
interface EdibleCollectionInterface
{
    /**
     * @return EdibleType
     */
    public function getAcceptableType(): EdibleType;

    /**
     * @param Edible $edible
     *
     * @return void
     *
     * @throws InvalidEdibleTypeException
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