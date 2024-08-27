<?php

declare(strict_types=1);

namespace App\Service\EdibleFormatter;

use App\Enum\EdibleUnit;
use App\Model\Edible;
use App\Service\EdibleStorage\EdibleStorageServiceInterface;
use App\Service\UnitConverter\UnitConverterInterface;

/**
 * Class EdibleFormatter.
 *
 * @package App\Service\EdibleFormatter
 */
readonly class EdibleFormatter implements EdibleFormatterInterface
{
    /**
     * @param UnitConverterInterface $unitConverter
     */
    public function __construct(private UnitConverterInterface $unitConverter)
    {
    }

    /**
     * @inheritDoc
     */
    public function format(Edible $edible, EdibleUnit $outputUnit = EdibleStorageServiceInterface::STORAGE_UNIT): array
    {
        $quantity = $edible->getQuantity();
        $unit = $edible->getUnit();

        if ($unit !== $outputUnit) {
            $quantity = $this->unitConverter->convert($quantity, $unit, $outputUnit);
            $unit = $outputUnit;
        }

        return [
            'id' => $edible->getId(),
            'type' => $edible->getType()->value,
            'name' => $edible->getName(),
            'quantity' => $quantity,
            'unit' => $unit->value,
        ];
    }
}