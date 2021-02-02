<?php

declare(strict_types=1);

namespace App\UseCase\Query;

use App\Entity\FlightInformation;
use App\Fetcher\FlightInformationFetcher;
use App\ViewModel\Chart\RunOutRotor;

class RunOutLeftHandler
{
    private FlightInformationFetcher $fetcher;

    public function __construct(FlightInformationFetcher $fetcher)
    {
        $this->fetcher = $fetcher;
    }

    public function handle(RunOutleftQuery $query): RunOutRotor
    {
        $runOutRotors = $this->fetcher->findByAirplane($query->airplane);

        $labels = [];
        $rndRaw = [];
        $rvdRaw = [];
        $rndCalc = [];
        $rvdCalc = [];
        /** @var FlightInformation $runOutRotor */
        foreach ($runOutRotors as $runOutRotor) {
            $labels[] = $runOutRotor->getId()->getDeparture();
            $rndRaw[] = $runOutRotor->getRunOutRotor()->getRndLeftRaw();
            $rvdRaw[] = $runOutRotor->getRunOutRotor()->getRvdLeftRaw();
            $rndCalc[] = $runOutRotor->getRunOutRotor()->getRndLeftCalc();
            $rvdCalc[] = $runOutRotor->getRunOutRotor()->getRvdLeftCalc();
        }

        return new RunOutRotor($labels, $rndRaw, $rvdRaw, $rndCalc, $rvdCalc);
    }
}