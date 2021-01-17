<?php

declare(strict_types=1);

namespace App\Entity;

use DateTimeImmutable;
use InvalidArgumentException;

class FlightInformationId
{
    private DateTimeImmutable $date;
    private int $departure;

    public function __construct(DateTimeImmutable $date, int $departure)
    {
        $this->setDeparture($departure);
        $this->setDate($date);
    }

    private function setDate(DateTimeImmutable $date): void
    {
        $this->date = $date;
    }

    private function setDeparture(int $departure): void
    {
        if ($departure < 1) {
            throw new InvalidArgumentException('Неверный формат номера вылета');
        }

        $this->departure = $departure;
    }

    public function getDate(): DateTimeImmutable
    {
        return $this->date;
    }

    public function getDeparture(): int
    {
        return $this->departure;
    }

    public function equals(self $id): bool
    {
        return $this == $id;
    }
}