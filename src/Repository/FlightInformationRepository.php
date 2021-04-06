<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\FlightInformation\FlightInformation;
use App\Entity\FlightInformation\FlightInformationId;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class FlightInformationRepository
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function find(FlightInformationId $id): ?FlightInformation //todo зачем тут исключение?
    {
        $repository = $this->getRepository();
        /** @var FlightInformation|null $flightInformation */
        $flightInformation = $repository->findOneBy([
            'flightInformationId.airplaneNumber' => $id->getAirplaneNumber(),
            'flightInformationId.flightDate' => $id->getFlightDate(),
            'flightInformationId.flightNumber' => $id->getFlightNumber(),
        ]);

        return $flightInformation;
    }

    public function findBySlug(string $slug): ?FlightInformation //todo зачем тут исключение?
    {
        $repository = $this->getRepository();
        /** @var FlightInformation|null $flightInformation */
        $flightInformation = $repository->findOneBy([
            'slug' => $slug,
        ]);
        return $flightInformation;
    }

    public function save(FlightInformation $flightInformation): void
    {
        $this->entityManager->persist($flightInformation);
        $this->entityManager->flush(); //todo заменить на транзакцию
    }

    public function delete(FlightInformation $flightInformation): void
    {
        $this->entityManager->remove($flightInformation);
        $this->entityManager->flush(); //todo заменить на транзакцию
    }

    private function getRepository(): EntityRepository
    {
        /** @var EntityRepository $repository */
        $repository = $this->entityManager->getRepository(FlightInformation::class);
        return $repository;
    }
}