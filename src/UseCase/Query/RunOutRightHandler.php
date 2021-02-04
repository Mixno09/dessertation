<?php

declare(strict_types=1);

namespace App\UseCase\Query;

use App\Entity\FlightInformation;
use App\Fetcher\FlightInformationFetcher;
use App\ViewModel\Chart\RunOutRotor;
use Exception;

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
        $error = [];
        /** @var FlightInformation $runOutRotor */
        foreach ($runOutRotors as $runOutRotor) {
            try {
                if ($runOutRotor->getRunOutRotor()->getRndRightRaw() <= 0 ||
                    $runOutRotor->getRunOutRotor()->getRvdRightRaw() <= 0 ||
                    $runOutRotor->getRunOutRotor()->getRndRightCalc() <= 0 ||
                    $runOutRotor->getRunOutRotor()->getRvdRightCalc() <= 0 ||
                    $runOutRotor->getRunOutRotor()->getRndRightCalc() >= 100 ||
                    $runOutRotor->getRunOutRotor()->getRvdRightCalc() >= 100) {
                    throw new Exception('Самолет с № ' . $runOutRotor->getId()->getAirplane() . ', вылетом № ' . $runOutRotor->getId()->getDeparture() . ' нужно проверить в ручную!');
                }
                $labels[] = $runOutRotor->getId()->getDeparture();
                $rndRaw[] = $runOutRotor->getRunOutRotor()->getRndRightRaw();
                $rvdRaw[] = $runOutRotor->getRunOutRotor()->getRvdRightRaw();
                $rndCalc[] = $runOutRotor->getRunOutRotor()->getRndRightCalc();
                $rvdCalc[] = $runOutRotor->getRunOutRotor()->getRvdRightCalc();
            } catch (Exception $exception) {
                $error[] = $exception->getMessage();
            }
        }

        return new RunOutRotor($labels, $rndRaw, $rvdRaw, $rndCalc, $rvdCalc, $error);
    }
}