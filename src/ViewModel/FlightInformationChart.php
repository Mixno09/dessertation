<?php

declare(strict_types=1);

namespace App\ViewModel;

use App\Entity\FlightInformation;

class FlightInformationChart
{
    private FlightInformation $flightInformation;

    public function __construct(FlightInformation $flightInformation)
    {
        $this->flightInformation = $flightInformation;
    }

    public function labels(): array
    {
        return array_map(
            fn($point) => $point->getTime(),
            $this->flightInformation->getPoints()
        );
    }

    public function t4Right(): array
    {
        return array_map(
            fn($point) => $point->getT4Right(),
            $this->flightInformation->getPoints()
        );
    }

    public function t4Left(): array
    {
        return array_map(
            fn($point) => $point->getT4Left(),
            $this->flightInformation->getPoints()
        );
    }

    public function alfaRight(): array
    {
        return array_map(
            fn($point) => $point->getAlfaRUDRight(),
            $this->flightInformation->getPoints()
        );
    }

    public function alfaLeft(): array
    {
        return array_map(
            fn($point) => $point->getAlfaRUDLeft(),
            $this->flightInformation->getPoints()
        );
    }

    public function rndRight(): array
    {
        return array_map(
            fn($point) => $point->getRndRight(),
            $this->flightInformation->getPoints()
        );
    }

    public function rndLeft(): array
    {
        return array_map(
            fn($point) => $point->getRndLeft(),
            $this->flightInformation->getPoints()
        );
    }

    public function rvdRight(): array
    {
        return array_map(
            fn($point) => $point->getRvdRight(),
            $this->flightInformation->getPoints()
        );
    }

    public function rvdLeft(): array
    {
        return array_map(
            fn($point) => $point->getRvdLeft(),
            $this->flightInformation->getPoints()
        );
    }

    public function t4Ticks(): array
    {
        $min = 0;
        $max = 0;
        $points = $this->flightInformation->getPoints();
        foreach ($points as $point) {
            $t4Right = $point->getT4Right();
            $t4Left = $point->getT4Left();
            $min = min($t4Right, $t4Left, $min);
            $max = max($t4Right, $t4Left, $max);
        }
        $min = (int) (floor($min / 100) * 100);
        $max = (int) (ceil($max / 100) * 100);
        $scale = $max - $min;
        $min -= $scale * 3;
        return ['min' => $min, 'max' => $max];
    }

    public function alfaRudTicks(): array
    {
        $min = 0;
        $max = 0;
        $points = $this->flightInformation->getPoints();
        foreach ($points as $point) {
            $t4Right = $point->getAlfaRUDRight();
            $t4Left = $point->getAlfaRUDLeft();
            $min = min($t4Right, $t4Left, $min);
            $max = max($t4Right, $t4Left, $max);
        }
        $min = (int) (floor($min / 10) * 10);
        $max = (int) (ceil($max / 10) * 10);
        $scale = $max - $min;
        $max += $scale;
        $min -= $scale * 2;
        return ['min' => $min, 'max' => $max];
    }

    public function rndTicks(): array
    {
        $min = 0;
        $max = 0;
        $points = $this->flightInformation->getPoints();
        foreach ($points as $point) {
            $t4Right = $point->getRndRight();
            $t4Left = $point->getRndLeft();
            $min = min($t4Right, $t4Left, $min);
            $max = max($t4Right, $t4Left, $max);
        }
        $min = (int) (floor($min / 25) * 25);
        $max = (int) (ceil($max / 25) * 25);
        $scale = $max - $min;
        $max += $scale * 2;
        $min -= $scale;
        return ['min' => $min, 'max' => $max];
    }

    public function rvdTicks(): array
    {
        $min = 0;
        $max = 0;
        $points = $this->flightInformation->getPoints();
        foreach ($points as $point) {
            $t4Right = $point->getRvdRight();
            $t4Left = $point->getRvdLeft();
            $min = min($t4Right, $t4Left, $min);
            $max = max($t4Right, $t4Left, $max);
        }
        $min = (int) (floor($min / 25) * 25);
        $max = (int) (ceil($max / 25) * 25);
        $scale = $max - $min;
        $max += $scale * 3;
        return ['min' => $min, 'max' => $max];
    }
}