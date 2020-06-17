<?php

declare(strict_types=1);

namespace App\Action;

final class Command
{
    /**
     * @var integer
     */
    public $numberAirplane;
    /**
     * @var \DateTime
     */
    public $date;
    /**
     * @var integer
     */
    public $numberFlight;
    /**
     * @var \Symfony\Component\HttpFoundation\File\File
     */
    public $flightInformation;
}