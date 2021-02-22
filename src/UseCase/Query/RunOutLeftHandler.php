<?php

declare(strict_types=1);

namespace App\UseCase\Query;

use App\Entity\FlightInformation\FlightInformation;
use App\Fetcher\FlightInformationFetcher;
use App\ViewModel\Chart\RunOutRotor;
use Exception;

class RunOutLeftHandler
{
    private FlightInformationFetcher $fetcher;

    public function __construct(FlightInformationFetcher $fetcher)
    {
        $this->fetcher = $fetcher;
    }

    public function handle(RunOutleftQuery $query): RunOutRotor
    {
        $flightInformations = $this->fetcher->findByAirplane($query->airplane);

        $labels = [];
        $rndRaw = [];
        $rvdRaw = [];
        $rndCalc = [];
        $rvdCalc = [];
        $errors = [];
        /** @var FlightInformation $flightInformation */
        foreach ($flightInformations as $flightInformation) {
            if ($flightInformation->isLeftError()) {
                $errors[] = 'Самолет с № ' . $flightInformation->getFlightInformationId()->getAirplaneNumber() . ', вылетом № ' . $flightInformation->getFlightInformationId()->getFlightNumber() . ' нужно проверить в ручную!';
                continue;
            }
            $labels[] = $flightInformation->getFlightInformationId()->getFlightNumber();
            $rndRaw[] = $flightInformation->getRunOutRotor()->getRndLeftRaw();
            $rvdRaw[] = $flightInformation->getRunOutRotor()->getRvdLeftRaw();
            $rndCalc[] = $flightInformation->getRunOutRotor()->getRndLeftCalc();
            $rvdCalc[] = $flightInformation->getRunOutRotor()->getRvdLeftCalc();
        }

        return new RunOutRotor($labels, $rndRaw, $rvdRaw, $rndCalc, $rvdCalc, $errors);
    }
}