<?php

declare(strict_types=1);

namespace App\Exception;

use Exception;

final class EntityNotExistsException extends Exception implements ExceptionInterface
{
}