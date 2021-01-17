<?php

declare(strict_types=1);

namespace App\UseCase\Command;

use App\Entity\Airplane;
use App\Entity\AirplaneId;
use App\Entity\FlightInformation;
use App\Entity\FlightInformationId;
use App\Repository\AirplaneRepository;
use App\Repository\FlightInformationRepository;
use App\Service\FlightInformationDataXlsParser;
use Exception;

class ImportFlightInformationFromXlsHandler
{
    private AirplaneRepository $repository;
    private FlightInformationDataXlsParser $parser;

    public function __construct(AirplaneRepository $repository, FlightInformationDataXlsParser $parser)
    {
        $this->repository = $repository;
        $this->parser = $parser;
    }

    public function handle(ImportFlightInformationFromXlsCommand $command): void
    {
        $airplaneId = new AirplaneId($command->airplane);
        $airplane = $this->repository->find($airplaneId);
        if ($airplane instanceof Airplane) {
            throw new Exception('Борт с номером ' . $command->airplane . ' Уже существует в базе данных');
        }

        $id = new FlightInformationId($command->date, $command->departure);
        $points = $this->parser->parse($command->flightInformation);
        $flightInformation = new FlightInformation($id, $points);
        $airplane = new Airplane($airplaneId);
        $airplane->addFlightInformation($flightInformation);

        $this->repository->save($airplane);
    }
}