<?php

declare(strict_types=1);

namespace App\Filters;

use App\Model\Edible;

readonly class EdibleFilter
{
    public function __construct(
        private ?string $keyword = null,
        private ?float $minGrams = null,
        private ?float $maxGrams = null,
    ) {
    }

    public function matches(Edible $edible): bool
    {
        if ($this->keyword && !str_contains(mb_strtolower($edible->getName()), mb_strtolower($this->keyword))) {
            return false;
        }

        if ($this->minGrams && $edible->getQuantity() < $this->minGrams) {
            return false;
        }

        if ($this->maxGrams && $edible->getQuantity() > $this->maxGrams) {
            return false;
        }

        return true;
    }
}