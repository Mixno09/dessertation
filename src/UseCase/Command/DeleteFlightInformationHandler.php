<?php

declare(strict_types=1);

namespace App\UseCase\Command;

use App\Repository\FlightInformationRepository;

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

        $this->repository->delete($flightInformation);
    }
}