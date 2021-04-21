<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\FlightInformation\FlightInformationFactory;
use App\Entity\FlightInformation\FlightInformationId;
use App\Repository\FlightInformationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

class FlightInformationService
{
    private FlightInformationRepository $repository;
    private EntityManagerInterface $entityManager;

    public function __construct(FlightInformationRepository $repository, EntityManagerInterface $entityManager)
    {
        $this->repository = $repository;
        $this->entityManager = $entityManager;
    }

    public function create(CreateFlightInformationCommand $command): FlightInformationId
    {
        $hasFlightInformation = $this->repository->hasOneByFlightInformationId(
            $command->getAirplaneNumber(),
            $command->getFlightDate(),
            $command->getFlightNumber()
        );
        if ($hasFlightInformation) {
            throw new Exception('Такие данные уже существуют в хранилище');
        }

        $flightInformation = FlightInformationFactory::create(
            $command->getAirplaneNumber(),
            $command->getFlightDate(),
            $command->getFlightNumber(),
            $command->getTime(),
            $command->getT4Right(),
            $command->getT4Left(),
            $command->getAlfaRudLeft(),
            $command->getAlfaRudRight(),
            $command->getRndLeft(),
            $command->getRvdLeft(),
            $command->getRndRight(),
            $command->getRvdRight()
        );

        $this->entityManager->persist($flightInformation);
        $this->entityManager->flush();

        return $flightInformation->getFlightInformationId();
    }

    public function delete(DeleteFlightInformationCommand $command): void
    {
        $flightInformation = $this->repository->findOneByFlightInformationId(
            $command->getAirplaneNumber(),
            $command->getFlightDate(),
            $command->getFlightNumber()
        );

        if ($flightInformation === null) {
            throw new Exception('Таких данных не существует в хранилище');
        }

        $this->entityManager->remove($flightInformation);
        $this->entityManager->flush();
    }
}