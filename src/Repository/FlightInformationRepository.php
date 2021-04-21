<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\FlightInformation\FlightInformation;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;

class FlightInformationRepository
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function hasOneByFlightInformationId(int $airplaneNumber, DateTimeImmutable $flightDate, int $flightNumber): bool
    {
        //todo реализовать
    }

    public function findOneByFlightInformationId(int $airplaneNumber, DateTimeImmutable $flightDate, int $flightNumber): ?FlightInformation
    {
        //todo реализовать
    }
}