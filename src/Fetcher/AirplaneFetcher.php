<?php

declare(strict_types=1);

namespace App\Fetcher;

use App\Entity\FlightInformation\FlightInformation;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadataInfo;

class AirplaneFetcher
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @return FlightInformation[]
     */
    public function getItemsWithLeftEngineParametersByAirplaneNumber(int $airplaneNumber): array
    {
        return $this->entityManager
            ->createQuery('SELECT f FROM App\Entity\FlightInformation\FlightInformation f WHERE f.flightInformationId.airplaneNumber = :airplaneNumber ORDER BY f.flightInformationId.flightDate')
            ->setParameter('airplaneNumber', $airplaneNumber)
            ->setFetchMode(FlightInformation::class, 'leftEngineParameters', ClassMetadataInfo::FETCH_EAGER)
            ->getResult();
    }

    public function getRightAverageParameterByAirplaneNumber(int $airplaneNumber): array //todo указать тип
    {
        return $this->entityManager
            ->createQuery('SELECT epc FROM App\Entity\FlightInformation\EngineParameterCollection epc, App\Entity\FlightInformation\FlightInformation f WHERE f.flightInformationId.airplaneNumber = :airplaneNumber AND f.rightEngineParameters = epc')
            ->setParameter('airplaneNumber', $airplaneNumber)
            ->getResult();
    }

    public function getFlightNumberByAirplaneNumber(int $airplaneNumber): array //todo указать тип
    {
        return $this->entityManager
            ->createQuery('SELECT f.flightInformationId.flightNumber FROM App\Entity\FlightInformation\FlightInformation f WHERE f.flightInformationId.airplaneNumber = :airplaneNumber')
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