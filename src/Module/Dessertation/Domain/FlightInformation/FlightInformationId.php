<?php

declare(strict_types=1);

namespace App\Module\Dessertation\Domain\FlightInformation;

use DateTimeImmutable;
use InvalidArgumentException;

class FlightInformationId
{
    private int $airplane;
    private DateTimeImmutable $date; //todo придумать формат
    private int $departure;

    public function __construct(int $airplane, DateTimeImmutable $date, int $departure) //todo отвалидировать дату и придумать формат времени
    {
        $this->setAirplane($airplane);
        $this->setDeparture($departure);
        $this->setDate($date);
    }

    private function setAirplane(int $airplane): void
    {
        if ($airplane < 1) {
            throw new InvalidArgumentException('Неверный формат номера самолета');
        }

        $this->airplane = $airplane;
    }

    public function setDate(DateTimeImmutable $date): void
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

    public function getAirplane(): int
    {
        return $this->airplane;
    }

    public function getDate(): DateTimeImmutable
    {
        return $this->date;
    }

    public function getDeparture(): int
    {
        return $this->departure;
    }
}