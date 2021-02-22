<?php

/** @noinspection PhpUnusedPrivateFieldInspection */

declare(strict_types=1);

namespace App\Entity\FlightInformation;

use InvalidArgumentException;

class FlightInformationPoint
{
    private $primaryKey;
    private int $time;
    private float $t4Right;
    private float $t4Left;
    private float $alfaRUDLeft;
    private float $alfaRUDRight;
    private float $rndLeft;
    private float $rvdLeft;
    private float $rndRight;
    private float $rvdRight;

    public function __construct(
        int $time,
        float $t4Right,
        float $t4Left,
        float $alfaRUDLeft,
        float $alfaRUDRight,
        float $rndLeft,
        float $rvdLeft,
        float $rndRight,
        float $rvdRight
    ) {
        $this->setTime($time);
        $this->setT4Right($t4Right);
        $this->setT4Left($t4Left);
        $this->setAlfaRUDLeft($alfaRUDLeft);
        $this->setAlfaRUDRight($alfaRUDRight);
        $this->setRndLeft($rndLeft);
        $this->setRvdLeft($rvdLeft);
        $this->setRndRight($rndRight);
        $this->setRvdRight($rvdRight);
    }

    public function getTime(): int
    {
        return $this->time;
    }

    private function setTime(int $time): void
    {
        if ($time < 0) {
            throw new InvalidArgumentException('Неверный формат времени');
        }
        $this->time = $time;
    }

    public function getT4Left(): float
    {
        return $this->t4Left;
    }

    private function setT4Left(float $t4Left): void
    {
        $this->t4Left = $t4Left;
    }

    public function getT4Right(): float
    {
        return $this->t4Right;
    }

    private function setT4Right(float $t4Right): void
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