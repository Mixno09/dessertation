<?php

declare(strict_types=1);

namespace App\Entity\FlightInformation;

use InvalidArgumentException;

class EngineParameter
{
    private ?int $id;
    private int $time;
    private float $t4;
    private float $alfaRUD;
    private float $rnd;
    private float $rvd;

    public function __construct(int $time, float $t4, float $alfaRUD, float $rnd, float $rvd)
    {
        $this->setTime($time);
        $this->t4 = $t4;
        $this->alfaRUD = $alfaRUD;
        $this->rnd = $rnd;
        $this->rvd = $rvd;
    }

    private function setTime(int $time): void
    {
        if ($time < 0) {
            throw new InvalidArgumentException('Время не может быть отрицательным');
        }
        $this->time = $time;
    }

    public function getTime(): int
    {
        return $this->time;
    }

    public function getT4(): float
    {
        return $this->t4;
    }

    public function getAlfaRUD(): float
    {
        return $this->alfaRUD;
    }

    public function getRnd(): float
    {
        return $this->rnd;
    }

    public function getRvd(): float
    {
        return $this->rvd;
    }

}