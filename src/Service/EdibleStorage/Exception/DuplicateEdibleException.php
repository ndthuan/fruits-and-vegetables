<?php

declare(strict_types=1);

namespace App\Service\EdibleStorage\Exception;

use RuntimeException;

/**
 * Class DuplicateEdibleException.
 *
 * Thrown when an edible is attempted to be added to a collection that already contains an edible with the same ID.
 *
 * @package App\Service\EdibleStorage\Exception
 */
class DuplicateEdibleException extends RuntimeException
{

}