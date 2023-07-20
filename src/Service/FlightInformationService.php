<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\FlightInformation\FlightInformation;
use App\Entity\FlightInformation\FlightInformationFactory;
use App\Exception\EntityExistsException;
use App\Exception\EntityNotExistsException;
use App\Repository\FlightInformationRepository;
use Doctrine\ORM\EntityManagerInterface;

class FlightInformationService
{
    private FlightInformationRepository $repository;
    private EntityManagerInterface $entityManager;

    public function __construct(FlightInformationRepository $repository, EntityManagerInterface $entityManager)
    {
        $this->repository = $repository;
        $this->entityManager = $entityManager;
    }

    /**
     * @throws EntityExistsException
     */
    public function create(CreateFlightInformationCommand $command): void
    {
        $flightInformation = $this->repository->findFlightInformationByFlightInformationId(
            $command->getAirplaneNumber(),
            $command->getFlightDate(),
            $command->getFlightNumber()
        );
        if ($flightInformation instanceof FlightInformation) {
            throw new EntityExistsException(sprintf(
                'Вылет уже существует. Параметры: номер самолета - %d, дата - %s, номер вылета - %d.',
                $command->getAirplaneNumber(),
                $command->getFlightDate()->format('Y-m-d'),
                $command->getFlightNumber()
            ));
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
    }

    /**
     * @throws EntityNotExistsException
     */
    public function delete(DeleteFlightInformationCommand $command): void
    {
        $flightInformation = $this->repository->findFlightInformationByFlightInformationId(
            $command->getAirplaneNumber(),
            $command->getFlightDate(),
            $command->getFlightNumber()
        );
        if ($flightInformation === null) {
            throw new EntityNotExistsException(sprintf(
                'Вылета не существует. Параметры: номер самолета - %d, дата - %s, номер вылета - %d.',
                $command->getAirplaneNumber(),
                $command->getFlightDate()->format('Y-m-d'),
                $command->getFlightNumber()
            ));
        }

        $this->entityManager->remove($flightInformation);
        $this->entityManager->flush();
    }
}