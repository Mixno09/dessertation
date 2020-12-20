<?php

declare(strict_types=1);

namespace App\Module\Dessertation\Application\Command;

use App\Module\Dessertation\Application\Service\FlightInformationDataXlsParser;
use App\Module\Dessertation\Domain\FlightInformation\FlightInformation;
use App\Module\Dessertation\Domain\FlightInformation\FlightInformationId;
use App\Module\Dessertation\Domain\FlightInformation\FlightInformationRepositoryInterface;
use Exception;

class ImportFlightInformationFromXlsHandler
{
    private FlightInformationRepositoryInterface $repository;
    private FlightInformationDataXlsParser $parser;

    public function __construct(FlightInformationRepositoryInterface $repository, FlightInformationDataXlsParser $parser)
    {
        $this->repository = $repository;
        $this->parser = $parser;
    }

    public function handle(ImportFlightInformationFromXlsCommand $command): void
    {
        $id = new FlightInformationId($command->numberAirplane, $command->date, $command->departure);

        $flightInformation = $this->repository->find($id);
        if ($flightInformation instanceof FlightInformation) {
            throw new Exception('Такие данные уже существуют в хранилище');
        }

        $data = $this->parser->parse($command->flightInformation);

        $flightInformation = new FlightInformation($id, $data);
        $this->repository->save($flightInformation);
    }
}