<?php

declare(strict_types=1);

namespace App\UseCase\Command;

use App\Fetcher\FlightInformationFetcher;
use App\Repository\FlightInformationRepository;
use Exception;

class DeleteFlightInformationHandler
{
    private FlightInformationFetcher $fetcher;
    private FlightInformationRepository $repository;

    public function __construct(FlightInformationFetcher $fetcher, FlightInformationRepository $repository)
    {
        $this->fetcher = $fetcher;
        $this->repository = $repository;
    }

    public function handle(DeleteFlightInformationCommand $command)
    {
        try {
            $flightInformation = $this->fetcher->findBySlug($command->slug);
        } catch (Exception $e) {
            echo $e->getMessage();
        }

        $this->repository->delete($flightInformation);
    }
}