<?php

declare(strict_types=1);

namespace App\UseCase\Query;

use App\Fetcher\FlightInformationFetcher;
use App\ViewModel\Chart\RunOutRotor;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class RunOutLeftHandler
{
    private FlightInformationFetcher $fetcher;

    public function __construct(FlightInformationFetcher $fetcher)
    {
        $this->fetcher = $fetcher;
    }

    public function handle(RunOutLeftChartQuery $query): RunOutRotor
    {
        $flightInformation = $this->fetcher->findByAirplane($query->getAirplane());
        if (count($flightInformation) === 0) {
            throw new NotFoundHttpException(); //todo написать сообщение
        }

        $labels = [];
        $rndRaw = [];
        $rvdRaw = [];
        $rndCalc = [];
        $rvdCalc = [];
        foreach ($flightInformation as $information) {
            $labels[] = $information->getId()->getDeparture();
            $rndRaw[] = $information->getRunOutRotor()->getRndLeftRaw();
            $rvdRaw[] = $information->getRunOutRotor()->getRvdLeftRaw();
            $rndCalc[] = $information->getRunOutRotor()->getRndLeftCalc();
            $rvdCalc[] = $information->getRunOutRotor()->getRvdLeftCalc();
        }

        return new RunOutRotor($labels, $rndRaw, $rvdRaw, $rndCalc, $rvdCalc);
    }
}