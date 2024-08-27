<?php

declare(strict_types=1);

namespace App\Service\EdibleStorage\Collection;

use App\Enum\EdibleType;

class VegetableCollection extends AbstractEdibleCollection
{
    /**
     * @inheritDoc
     */
    public function getAcceptableType(): EdibleType
    {
        return EdibleType::VEGETABLE;
    }
}