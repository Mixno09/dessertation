<?php

declare(strict_types=1);

namespace App\UseCase\Command;

use App\Entity\FlightInformation;
use App\Repository\FlightInformationRepository;
use Exception;

class DeleteFlightInformationHandler
{
    private FlightInformationRepository $repository;

    public function __construct(FlightInformationRepository $repository)
    {
        $this->repository = $repository;
    }

    public function handle(DeleteFlightInformationCommand $command)
    {
       $flightInformation = $this->repository->findBySlug($command->slug);

        if (! $flightInformation instanceof FlightInformation) {
            throw new Exception(); //todo написать сообщение
        }

        $this->repository->delete($flightInformation);
    }
}