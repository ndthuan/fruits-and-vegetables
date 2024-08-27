<?php

declare(strict_types=1);

namespace App\Service\EdibleStorage;

use App\Model\Edible;

/**
 * Interface RequestReaderInterface.
 *
 * @package App\Service\EdibleStorage
 */
interface RequestReaderInterface
{
    /**
     * @return Edible[]
     */
    public function read(): array;
}