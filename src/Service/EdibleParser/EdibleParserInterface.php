<?php

declare(strict_types=1);

namespace App\Service\EdibleParser;

use App\Model\Edible;

/**
 * Interface EdibleParserInterface.
 *
 * @package App\Service\EdibleParser
 */
interface EdibleParserInterface
{
    /**
     * Parses an edible item from an array and converts its unit to the base unit.
     *
     * @param array $item
     *
     * @return Edible|null
     */
    public function parseEdible(array $item): ?Edible;
}