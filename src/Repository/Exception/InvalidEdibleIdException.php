<?php

declare(strict_types=1);

namespace App\Repository\Exception;

use RuntimeException;

/**
 * Class InvalidEdibleIdException.
 *
 * Thrown when an edible with an invalid ID is attempted to be added to the repository.
 *
 * @package App\Repository\Exception
 */
class InvalidEdibleIdException extends RuntimeException
{

}