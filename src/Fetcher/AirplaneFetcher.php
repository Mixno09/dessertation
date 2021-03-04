<?php

declare(strict_types=1);

namespace App\Fetcher;

use Doctrine\ORM\EntityManagerInterface;

class AirplaneFetcher
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getLeftAverageParameterByAirplaneNumber(int $airplaneNumber): array
    {
        return $this->entityManager
            ->createQuery('SELECT epc FROM App\Entity\FlightInformation\EngineParameterCollection epc, App\Entity\FlightInformation\FlightInformation f WHERE f.flightInformationId.airplaneNumber = :airplaneNumber AND f.leftEngineParameters = epc')
            ->setParameter('airplaneNumber', $airplaneNumber)
            ->getResult();
    }

    public function getRightAverageParameterByAirplaneNumber(int $airplaneNumber): array
    {
        return $this->entityManager
            ->createQuery('SELECT epc FROM App\Entity\FlightInformation\EngineParameterCollection epc, App\Entity\FlightInformation\FlightInformation f WHERE f.flightInformationId.airplaneNumber = :airplaneNumber AND f.rightEngineParameters = epc')
            ->setParameter('airplaneNumber', $airplaneNumber)
            ->getResult();
    }

    public function getFlightNumberByAirplaneNumber(int $airplaneNumber): array
    {
        return $this->entityManager
            ->createQuery('SELECT f.flightInformationId.flightNumber FROM App\Entity\FlightInformation\FlightInformation f WHERE f.flightInformationId.airplaneNumber = :airplaneNumber') //todo как получить flightInformationId
            ->setParameter('airplaneNumber', $airplaneNumber)
            ->getResult();
    }

    public function count(): int
    {
        return (int) $this->entityManager
            ->createQuery('SELECT COUNT(DISTINCT f.flightInformationId.airplaneNumber) FROM App\Entity\FlightInformation\FlightInformation f')
            ->getSingleScalarResult();
    }

    /**
     * @return int[]
     */
    public function items(int $offset, int $limit): array
    {
        $rows = $this->entityManager
            ->createQuery('SELECT DISTINCT f.flightInformationId.airplaneNumber FROM App\Entity\FlightInformation\FlightInformation f ORDER BY f.flightInformationId.airplaneNumber')
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->getArrayResult();

        return array_column($rows, 'flightInformationId.airplaneNumber');
    }
}