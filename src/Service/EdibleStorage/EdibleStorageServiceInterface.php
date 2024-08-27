<?php

declare(strict_types=1);

namespace App\Service\EdibleStorage;

use App\Enum\EdibleType;
use App\Enum\EdibleUnit;
use App\Filters\EdibleFilter;
use App\Model\Edible;

/**
 * Interface EdibleStorageServiceInterface.
 *
 * @package App\Service\EdibleStorage
 */
interface EdibleStorageServiceInterface
{
    /** @var EdibleUnit  */
    public const STORAGE_UNIT = EdibleUnit::GRAM;

    /**
     * @param Edible $edible
     *
     * @return void
     */
    public function add(Edible $edible): void;

    /**
     * @param int $id
     *
     * @return void
     */
    public function remove(int $id): void;

    /**
     * @param EdibleType|null $type
     * @param EdibleFilter|null $filter
     *
     * @return array
     */
    public function list(?EdibleType $type, ?EdibleFilter $filter): array;
}