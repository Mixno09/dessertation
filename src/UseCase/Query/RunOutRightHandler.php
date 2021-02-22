<?php

declare(strict_types=1);

namespace App\UseCase\Query;

use App\Entity\FlightInformation\FlightInformation;
use App\Fetcher\FlightInformationFetcher;
use App\ViewModel\Chart\RunOutRotor;

class RunOutRightHandler
{
    private FlightInformationFetcher $fetcher;

    public function __construct(FlightInformationFetcher $fetcher)
    {
        $this->fetcher = $fetcher;
    }

    public function handle(RunOutRightQuery $query): RunOutRotor
    {
        $runOutRotors = $this->fetcher->findByAirplane($query->airplane);

        $labels = [];
        $rndRaw = [];
        $rvdRaw = [];
        $rndCalc = [];
        $rvdCalc = [];
        $errors = [];
        /** @var FlightInformation $runOutRotor */
        foreach ($runOutRotors as $runOutRotor) {
            if ($runOutRotor->isRightError()) {
                $errors[] = 'Самолет с № ' . $runOutRotor->getFlightInformationId()->getAirplaneNumber() . ', вылетом № ' . $runOutRotor->getFlightInformationId()->getFlightNumber() . ' нужно проверить в ручную!';
                continue;
            }
            $labels[] = $runOutRotor->getFlightInformationId()->getFlightNumber();
            $rndRaw[] = $runOutRotor->getRunOutRotor()->getRndRightRaw();
            $rvdRaw[] = $runOutRotor->getRunOutRotor()->getRvdRightRaw();
            $rndCalc[] = $runOutRotor->getRunOutRotor()->getRndRightCalc();
            $rvdCalc[] = $runOutRotor->getRunOutRotor()->getRvdRightCalc();
        }

        return new RunOutRotor($labels, $rndRaw, $rvdRaw, $rndCalc, $rvdCalc, $errors);
    }
}