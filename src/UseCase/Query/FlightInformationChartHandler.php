<?php

declare(strict_types=1);

namespace App\UseCase\Query;

use App\Entity\FlightInformationPoint;
use App\Fetcher\FlightInformationChartFetcher;
use App\ViewModel\Chart\FlightInformationChart;

class FlightInformationChartHandler
{
    private FlightInformationChartFetcher $informationChartFetcher;

    public function __construct(FlightInformationChartFetcher $informationChartFetcher)
    {
        $this->informationChartFetcher = $informationChartFetcher;
    }

    public function handle(FlightInformationChartQuery $query): FlightInformationChart
    {
        $points = $this->informationChartFetcher->findBySlug($query->slug);
        $flightInformationChart = new FlightInformationChart();
        /** @var FlightInformationPoint $point */
        foreach ($points as $point) { //todo инициализировать переменные
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
        $flightInformationChart->labels = $labels;
        $flightInformationChart->t4Right = $t4Right;
        $flightInformationChart->t4Left = $t4Left;
        $flightInformationChart->alfaRight = $alfaRight;
        $flightInformationChart->alfaLeft = $alfaLeft;
        $flightInformationChart->rndRight = $rndRight;
        $flightInformationChart->rndLeft = $rndLeft;
        $flightInformationChart->rvdRight = $rvdRight;
        $flightInformationChart->rvdLeft = $rvdLeft;
        $flightInformationChart->ticks(); //todo как сделать по другому
        
        return $flightInformationChart;
    }
}