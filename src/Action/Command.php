<?php

declare(strict_types=1);

namespace App\Action;

use Symfony\Component\HttpFoundation\File\File;

final class Command
{
    /**
     * @var int
     */
    public $numberAirplane;

    /**
     * @var \DateTime
     */
    public $date;

    /**
     * @var int
     */
    public $numberFlight;

    /**
     * @var File
     */
    public $flightInformation;
}