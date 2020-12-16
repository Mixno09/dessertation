<?php

declare(strict_types=1);

namespace App\Module\Dessertation\Domain\FlightInformation;

use InvalidArgumentException;

class FlightInformationDataPoint
{
    private int $time;
    private int $t4Left;
    private int $t4Right;
    private float $alfaRUDLeft;
    private float $alfaRUDRight;
    private float $rndLeft;
    private float $rndRight;
    private float $rvdLeft;
    private float $rvdRight;

    public function __construct(
        int $time,
        int $t4Left,
        int $t4Right,
        float $alfaRUDLeft,
        float $alfaRUDRight,
        float $rndLeft,
        float $rndRight,
        float $rvdLeft,
        float $rvdRight
    ) {
        $this->setTime($time);
        $this->setT4Left($t4Left);
        $this->setT4Right($t4Right);
        $this->setAlfaRUDLeft($alfaRUDLeft);
        $this->setAlfaRUDRight($alfaRUDRight);
        $this->setRndLeft($rndLeft);
        $this->setRndRight($rndRight);
        $this->setRvdLeft($rvdLeft);
        $this->setRvdRight($rvdRight);
    }

    public function getTime(): int
    {
        return $this->time;
    }

    private function setTime(int $time): void
    {
        if ($time >= 0) {
            $this->time = $time;
        }
        throw new InvalidArgumentException('Неверный формат времени');
    }

    public function getT4Left(): float
    {
        return $this->t4Left;
    }

    private function setT4Left(int $t4Left): void
    {
        $this->t4Left = $t4Left;
    }

    public function getT4Right(): float
    {
        return $this->t4Right;
    }

    private function setT4Right(int $t4Right): void
    {
        $this->t4Right = $t4Right;
    }

    public function getAlfaRUDLeft(): float
    {
        return $this->alfaRUDLeft;
    }

    private function setAlfaRUDLeft(float $alfaRUDLeft): void
    {
        $this->alfaRUDLeft = $alfaRUDLeft;
    }

    public function getAlfaRUDRight(): float
    {
        return $this->alfaRUDRight;
    }

    private function setAlfaRUDRight(float $alfaRUDRight): void
    {
        $this->alfaRUDRight = $alfaRUDRight;
    }

    public function getRndLeft(): float
    {
        return $this->rndLeft;
    }

    private function setRndLeft(float $rndLeft): void
    {
        $this->rndLeft = $rndLeft;
    }

    public function getRndRight(): float
    {
        return $this->rndRight;
    }

    private function setRndRight(float $rndRight): void
    {
        $this->rndRight = $rndRight;
    }

    public function getRvdLeft(): float
    {
        return $this->rvdLeft;
    }

    private function setRvdLeft(float $rvdLeft): void
    {
        $this->rvdLeft = $rvdLeft;
    }

    public function getRvdRight(): float
    {
        return $this->rvdRight;
    }

    private function setRvdRight(float $rvdRight): void
    {
        $this->rvdRight = $rvdRight;
    }
}