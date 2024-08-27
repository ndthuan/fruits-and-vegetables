<?php

declare(strict_types=1);

namespace App\Service\EdibleFormatter;

use App\Enum\EdibleUnit;
use App\Model\Edible;
use App\Service\EdibleStorage\EdibleStorageServiceInterface;

/**
 * Interface EdibleFormatterInterface.
 *
 * @package App\Service\EdibleFormatter
 */
interface EdibleFormatterInterface
{
    /**
     * @param Edible $edible
     * @param EdibleUnit $outputUnit
     *
     * @return array
     */
    public function format(Edible $edible, EdibleUnit $outputUnit = EdibleStorageServiceInterface::STORAGE_UNIT): array;
}