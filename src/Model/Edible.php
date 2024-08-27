<?php

declare(strict_types=1);

namespace App\Model;

use App\Enum\EdibleType;
use App\Enum\EdibleUnit;

/**
 * Class Edible.
 *
 * @package App\Model
 */
class Edible
{
    public function __construct(
        private string $name,
        private EdibleType $type,
        private float $quantity,
        private EdibleUnit $unit,
        private ?int $id = null,
    ){}

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getType(): EdibleType
    {
        return $this->type;
    }

    public function getQuantity(): float
    {
        return $this->quantity;
    }

    public function getUnit(): EdibleUnit
    {
        return $this->unit;
    }
}