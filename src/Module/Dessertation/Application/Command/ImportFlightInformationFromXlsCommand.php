<?php

declare(strict_types=1);

namespace App\Module\Dessertation\Application\Command;

use DateTimeImmutable;
use Symfony\Component\HttpFoundation\File\File;

class ImportFlightInformationFromXlsCommand
{
    public int $airplane;
    public DateTimeImmutable $date;
    public int $departure;
    public File $file;
}