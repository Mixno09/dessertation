<?php

declare(strict_types=1);

namespace App\UseCase\Query;

use App\Entity\FlightInformation;
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
        $runOutRotors = $this->fetcher->findByAirplane($query->airplane);

        $labels = [];
        $rndRaw = [];
        $rvdRaw = [];
        $rndCalc = [];
        $rvdCalc = [];
        $error = [];
        /** @var FlightInformation $runOutRotor */
        foreach ($runOutRotors as $runOutRotor) {
            try {
                if ($runOutRotor->getRunOutRotor()->getRndLeftRaw() <= 0 ||
                    $runOutRotor->getRunOutRotor()->getRvdLeftRaw() <= 0 ||
                    $runOutRotor->getRunOutRotor()->getRndLeftCalc() <= 0 ||
                    $runOutRotor->getRunOutRotor()->getRvdLeftCalc() <= 0) {
                    throw new Exception('Самолет с № ' . $runOutRotor->getId()->getAirplane() . ', вылетом № ' . $runOutRotor->getId()->getDeparture() . ' нужно проверить в ручную!');
                }
                $labels[] = $runOutRotor->getId()->getDeparture();
                $rndRaw[] = $runOutRotor->getRunOutRotor()->getRndLeftRaw();
                $rvdRaw[] = $runOutRotor->getRunOutRotor()->getRvdLeftRaw();
                $rndCalc[] = $runOutRotor->getRunOutRotor()->getRndLeftCalc();
                $rvdCalc[] = $runOutRotor->getRunOutRotor()->getRvdLeftCalc();
            } catch (Exception $exception) {
                $error[] = $exception->getMessage();
            }
        }

        return new RunOutRotor($labels, $rndRaw, $rvdRaw, $rndCalc, $rvdCalc, $error);
    }
}