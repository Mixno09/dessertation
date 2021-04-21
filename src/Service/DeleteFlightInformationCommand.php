<?php

declare(strict_types=1);

namespace App\Service;

use DateTimeImmutable;

class DeleteFlightInformationCommand
{
    private int $airplaneNumber;
    private DateTimeImmutable $flightDate;
    private int $flightNumber;

    public function __construct(int $airplaneNumber, DateTimeImmutable $flightDate, int $flightNumber)
    {
        $this->airplaneNumber = $airplaneNumber;
        $this->flightDate = $flightDate;
        $this->flightNumber = $flightNumber;
    }

    public function getAirplaneNumber(): int
    {
        return $this->airplaneNumber;
    }

    public function getFlightDate(): DateTimeImmutable
    {
        return $this->flightDate;
    }

    public function getFlightNumber(): int
    {
        return $this->flightNumber;
    }
}