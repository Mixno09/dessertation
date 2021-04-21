<?php

declare(strict_types=1);

namespace App\Service;

use DateTimeImmutable;

class CreateFlightInformationCommand
{
    private int $airplaneNumber;
    private DateTimeImmutable $flightDate;
    private int $flightNumber;
    private array $time;
    private array $t4Right;
    private array $t4Left;
    private array $alfaRudLeft;
    private array $alfaRudRight;
    private array $rndLeft;
    private array $rvdLeft;
    private array $rndRight;
    private array $rvdRight;

    public function __construct(
        int $airplaneNumber,
        DateTimeImmutable $flightDate,
        int $flightNumber,
        array $time,
        array $t4Right,
        array $t4Left,
        array $alfaRudLeft,
        array $alfaRudRight,
        array $rndLeft,
        array $rvdLeft,
        array $rndRight,
        array $rvdRight
    ) {
        $this->airplaneNumber = $airplaneNumber;
        $this->flightDate = $flightDate;
        $this->flightNumber = $flightNumber;
        $this->time = $time;
        $this->t4Right = $t4Right;
        $this->t4Left = $t4Left;
        $this->alfaRudLeft = $alfaRudLeft;
        $this->alfaRudRight = $alfaRudRight;
        $this->rndLeft = $rndLeft;
        $this->rvdLeft = $rvdLeft;
        $this->rndRight = $rndRight;
        $this->rvdRight = $rvdRight;
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

    public function getTime(): array
    {
        return $this->time;
    }

    public function getT4Right(): array
    {
        return $this->t4Right;
    }

    public function getT4Left(): array
    {
        return $this->t4Left;
    }

    public function getAlfaRudLeft(): array
    {
        return $this->alfaRudLeft;
    }

    public function getAlfaRudRight(): array
    {
        return $this->alfaRudRight;
    }

    public function getRndLeft(): array
    {
        return $this->rndLeft;
    }

    public function getRvdLeft(): array
    {
        return $this->rvdLeft;
    }

    public function getRndRight(): array
    {
        return $this->rndRight;
    }

    public function getRvdRight(): array
    {
        return $this->rvdRight;
    }
}