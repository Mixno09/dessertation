<?php

declare(strict_types=1);

namespace App\Entity\FlightInformation;

use DateTimeImmutable;
use InvalidArgumentException;

class FlightInformationId
{
    private int $airplaneNumber;
    private DateTimeImmutable $flightDate;
    private int $flightNumber;

    public function __construct(int $airplaneNumber, DateTimeImmutable $flightDate, int $flightNumber)
    {
        $this->setAirplaneNumber($airplaneNumber);
        $this->setFlightNumber($flightNumber);
        $this->flightDate = $flightDate;
    }

    private function setAirplaneNumber(int $airplaneNumber): void
    {
        if ($airplaneNumber < 1) {
            throw new InvalidArgumentException('Неверный формат номера самолета');
        }

        $this->airplaneNumber = $airplaneNumber;
    }

    private function setFlightNumber(int $flightNumber): void
    {
        if ($flightNumber < 1) {
            throw new InvalidArgumentException('Неверный формат номера вылета');
        }

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