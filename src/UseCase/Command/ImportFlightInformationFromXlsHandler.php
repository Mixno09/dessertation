<?php

declare(strict_types=1);

namespace App\UseCase\Command;

use App\Entity\FlightInformationRunOutRotor;
use App\Service\FlightInformationDataXlsParser;
use App\Entity\FlightInformation;
use App\Entity\FlightInformationId;
use App\Repository\FlightInformationRepository;
use Exception;

class ImportFlightInformationFromXlsHandler
{
    private FlightInformationRepository $repository;
    private FlightInformationDataXlsParser $parser;

    public function __construct(FlightInformationRepository $repository, FlightInformationDataXlsParser $parser)
    {
        $this->repository = $repository;
        $this->parser = $parser;
    }

    public function handle(ImportFlightInformationFromXlsCommand $command): void
    {
        $id = new FlightInformationId($command->airplane, $command->date, $command->departure);

        $flightInformation = $this->repository->find($id);
        if ($flightInformation instanceof FlightInformation) {
            throw new Exception('Такие данные уже существуют в хранилище');
        }

        $points = $this->parser->parse($command->flightInformation);
        $runOutRotor = new FlightInformationRunOutRotor($points);

        $flightInformation = new FlightInformation($id, $points, $runOutRotor);

        $this->repository->save($flightInformation);
    }
}