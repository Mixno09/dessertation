<?php

declare(strict_types=1);

namespace App\Action;

use DateTime;
use Symfony\Component\HttpFoundation\File\File;

final class Command
{
    public int $numberAirplane;

    public DateTime $date;

    public int $numberFlight;

    public File $flightInformation;
}