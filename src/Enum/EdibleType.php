<?php

declare(strict_types=1);

namespace App\Enum;

/**
 * Enum EdibleType.
 *
 * @package App\Model
 */
enum EdibleType: string
{
    case FRUIT = 'fruit';

    case VEGETABLE = 'vegetable';
}