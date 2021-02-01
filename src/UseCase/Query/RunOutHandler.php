<?php

declare(strict_types=1);

namespace App\UseCase\Query;

use App\Fetcher\RunOutRotorFetcher;
use App\ViewModel\Chart\RunOutLeftChart;
use App\ViewModel\Chart\RunOutRightChart;

class RunOutHandler
{
    private RunOutRotorFetcher $fetcher;

    public function __construct(RunOutRotorFetcher $fetcher)
    {
        $this->fetcher = $fetcher;
    }

    public function handle(RunOutChartQuery $query): array
    {
        $runOutRotors = $this->fetcher->findByAirplane($query->airplane);

        $runOutLeftChart = new RunOutLeftChart();
        $runOutRightChart = new RunOutRightChart();

        foreach ($runOutRotors as $runOutRotor) {
            $runOutLeftChart->labels[] = $runOutRotor['id.departure'];
            $runOutRightChart->labels[] = $runOutRotor['id.departure'];
            $runOutLeftChart->rndRaw[] = $runOutRotor['runOutRotor.rndLeftRaw'];
            $runOutRightChart->rndRaw[] = $runOutRotor['runOutRotor.rndRightRaw'];
            $runOutLeftChart->rvdRaw[] = $runOutRotor['runOutRotor.rvdLeftRaw'];
            $runOutRightChart->rvdRaw[] = $runOutRotor['runOutRotor.rvdRightRaw'];
            $runOutLeftChart->rndCalc[] = $runOutRotor['runOutRotor.rndLeftCalc'];
            $runOutRightChart->rndCalc[] = $runOutRotor['runOutRotor.rndRightCalc'];
            $runOutLeftChart->rvdCalc[] = $runOutRotor['runOutRotor.rvdLeftCalc'];
            $runOutRightChart->rvdCalc[] = $runOutRotor['runOutRotor.rvdRightCalc'];
        }

        return ['left' => $runOutLeftChart, 'right' => $runOutRightChart];
    }
}