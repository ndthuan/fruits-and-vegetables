<?php

declare(strict_types=1);

namespace App\Service\EdibleStorage\Exception;

use RuntimeException;

/**
 * Class EdibleNotFoundException.
 *
 * Thrown when an edible is attempted to be removed from a collection, but it does not exist.
 *
 * @package App\Service\EdibleStorage\Exception
 */
class EdibleNotFoundException extends RuntimeException
{

}