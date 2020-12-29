<?php

declare(strict_types=1);

namespace App\UseCase\Command;

use DateTimeImmutable;

class DeleteFlightInformationCommand
{
    public string $slug;
    public int $airplane;
    public DateTimeImmutable $date;
    public int $departure;
}