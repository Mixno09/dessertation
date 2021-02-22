<?php

declare(strict_types=1);

namespace App\UseCase\Query;

use App\Entity\FlightInformation\FlightInformationPoint;
use App\Fetcher\PointChartFetcher;
use App\ViewModel\Chart\FlightInformationChart;

class FlightInformationChartHandler
{
    private PointChartFetcher $informationChartFetcher;

    public function __construct(PointChartFetcher $informationChartFetcher)
    {
        $this->informationChartFetcher = $informationChartFetcher;
    }

    public function handle(FlightInformationChartQuery $query): FlightInformationChart
    {
        $points = $this->informationChartFetcher->findBySlug($query->slug);

        $labels = [];
        $t4Right = [];
        $t4Left = [];
        $alfaRight = [];
        $alfaLeft = [];
        $rndRight = [];
        $rndLeft = [];
        $rvdRight = [];
        $rvdLeft = [];
        /** @var FlightInformationPoint $point */
        foreach ($points as $point) {
            $labels[] = $point->getTime();
            $t4Right[] = $point->getT4Right();
            $t4Left[] = $point->getT4Left();
            $alfaRight[] = $point->getAlfaRUDRight();
            $alfaLeft[] = $point->getAlfaRUDLeft();
            $rndRight[] = $point->getRndRight();
            $rndLeft[] = $point->getRndLeft();
            $rvdRight[] = $point->getRvdRight();
            $rvdLeft[] = $point->getRvdLeft();
        }

        $t4Ticks = $this->t4Ticks($t4Right, $t4Left);
        $alfaRudTicks = $this->alfaRudTicks($alfaRight, $alfaLeft);
        $rndTicks = $this->rndTicks($rndRight, $rndLeft);
        $rvdTicks = $this->rvdTicks($rvdRight, $rvdLeft);

        return new FlightInformationChart(
            $labels,
            $t4Right,
            $t4Left,
            $alfaRight,
            $alfaLeft,
            $rndRight,
            $rndLeft,
            $rvdRight,
            $rvdLeft,
            $t4Ticks,
            $alfaRudTicks,
            $rndTicks,
            $rvdTicks
        );
    }

    private function t4Ticks(array $t4Right, array $t4Left): array
    {
        $min = 0;
        $max = 0;
        foreach ($t4Right as $right) {
            $min = min($right, $min);
            $max = max($right, $max);
        }
        foreach ($t4Left as $left) {
            $min = min($left, $min);
            $max = max($left, $max);
        }
        $min = (int)(floor($min / 100) * 100);
        $max = (int)(ceil($max / 100) * 100);
        $scale = $max - $min;
        $min -= $scale * 3;
        return ['min' => $min, 'max' => $max];
    }

    private function alfaRudTicks(array $alfaRight, array $alfaLeft): array
    {
        $min = 0;
        $max = 0;
        foreach ($alfaRight as $right) {
            $min = min($right, $min);
            $max = max($right, $max);
        }
        foreach ($alfaLeft as $left) {
            $min = min($left, $min);
            $max = max($left, $max);
        }
        $min = (int)(floor($min / 10) * 10);
        $max = (int)(ceil($max / 10) * 10);
        $scale = $max - $min;
        $max += $scale;
        $min -= $scale * 2;
        return ['min' => $min, 'max' => $max];
    }

    private function rndTicks(array $rndRight, array $rndLeft): array
    {
        $min = 0;
        $max = 0;
        foreach ($rndRight as $right) {
            $min = min($right, $min);
            $max = max($right, $max);
        }
        foreach ($rndLeft as $left) {
            $min = min($left, $min);
            $max = max($left, $max);
        }
        $min = (int)(floor($min / 25) * 25);
        $max = (int)(ceil($max / 25) * 25);
        $scale = $max - $min;
        $max += $scale * 2;
        $min -= $scale;
        return ['min' => $min, 'max' => $max];
    }

    private function rvdTicks(array $rvdRight, array $rvdLeft): array
    {
        $min = 0;
        $max = 0;
        foreach ($rvdRight as $right) {
            $min = min($right, $min);
            $max = max($right, $max);
        }
        foreach ($rvdLeft as $left) {
            $min = min($left, $min);
            $max = max($left, $max);
        }
        $min = (int)(floor($min / 25) * 25);
        $max = (int)(ceil($max / 25) * 25);
        $scale = $max - $min;
        $max += $scale * 3;
        return ['min' => $min, 'max' => $max];
    }
}

