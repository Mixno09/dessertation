<?php

declare(strict_types=1);

namespace App\Domain;

use DateTimeImmutable;
use InvalidArgumentException;

class DepartureId
{
    private int $airplane;
    private DateTimeImmutable $date; //todo придумать формат
    private int $departure;

    public function __construct(int $airplane, DateTimeImmutable $date, int $departure) //todo отвалидировать дату и придумать формат времени
    {
        $this->setAirplane($airplane);
        $this->setDeparture($departure);
        $this->date = $date;
    }

    private function setAirplane(int $airplane): void
    {
        if ($airplane >= 1) {
            $this->airplane = $airplane;
        }

        throw new InvalidArgumentException('Неверный формат номера самолета');
    }

    private function setDeparture(int $departure): void
    {
        if ($departure >= 1) {
            $this->departure = $departure;
        }

        throw new InvalidArgumentException('Неверный формат номера вылета');
    }

    public function getAirplane(): int
    {
        return $this->airplane;
    }

    public function getDeparture(): int
    {
        return $this->departure;
    }
}