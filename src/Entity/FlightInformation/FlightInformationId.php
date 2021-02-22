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
        $this->setAirplane($airplaneNumber);
        $this->setDeparture($flightNumber);
        $this->flightDate = $flightDate;
    }

    private function setAirplane(int $airplane): void
    {
        if ($airplane < 1) {
            throw new InvalidArgumentException('Неверный формат номера самолета');
        }

        $this->airplaneNumber = $airplane;
    }

    private function setDeparture(int $departure): void
    {
        if ($departure < 1) {
            throw new InvalidArgumentException('Неверный формат номера вылета');
        }

        $this->flightNumber = $departure;
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