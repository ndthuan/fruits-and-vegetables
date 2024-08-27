<?php

declare(strict_types=1);

namespace App\Service\EdibleParser;

use App\Enum\EdibleType;
use App\Enum\EdibleUnit;
use App\Model\Edible;
use App\Service\EdibleStorage\EdibleStorageServiceInterface;
use App\Service\UnitConverter\UnitConverterInterface;

/**
 * Class EdibleParser.
 *
 * @package App\Service\EdibleParser
 */
readonly class EdibleParser implements EdibleParserInterface
{
    public function __construct(private UnitConverterInterface $unitConverter)
    {}

    /**
     * @inheritDoc
     */
    public function parseEdible(array $item): ?Edible
    {
        $id = $item['id'] ?? null;
        $name = $item['name'] ?? null;
        $unit = EdibleUnit::tryFrom($item['unit'] ?? '');
        $quantity = $item['quantity'] ?? null;
        $type = EdibleType::tryFrom($item['type'] ?? '');

        if ($name === null || $unit === null || $quantity === null || $type === null) {
            return null;
        }

        if ($unit !== EdibleUnit::GRAM && $unit !== EdibleUnit::KILOGRAM) {
            return null;
        }

        if ($type !== EdibleType::FRUIT && $type !== EdibleType::VEGETABLE) {
            return null;
        }

        if ($unit !== EdibleStorageServiceInterface::STORAGE_UNIT) {
            $quantity = $this->unitConverter->convert($quantity, $unit, EdibleStorageServiceInterface::STORAGE_UNIT);
            $unit = EdibleStorageServiceInterface::STORAGE_UNIT;
        }

        return new Edible($name, $type, $quantity, $unit, $id);
    }
}