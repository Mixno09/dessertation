<?php

declare(strict_types=1);

namespace App\UseCase\Command;

use App\Repository\FlightInformationRepository;
use Doctrine\ORM\EntityManagerInterface;

class DeleteFlightInformationHandler
{
    private FlightInformationRepository $repository;
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager, FlightInformationRepository $repository)
    {
        $this->entityManager = $entityManager;
        $this->repository = $repository;
    }

    public function handle(DeleteFlightInformationCommand $command)
    {
       $flightInformation = $this->repository->findBySlug($command->slug);
       $this->entityManager->remove($flightInformation);
       $this->entityManager->flush();
    }
}