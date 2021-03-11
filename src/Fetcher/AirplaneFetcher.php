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
            ->createQuery('SELECT f FROM App\Entity\FlightInformation\FlightInformation f WHERE f.flightInformationId.airplaneNumber = :airplaneNumber ORDER BY f.flightInformationId.flightDate, f.flightInformationId.flightNumber')
            ->setParameter('airplaneNumber', $airplaneNumber)
            ->setFetchMode(FlightInformation::class, 'leftEngineParameters', ClassMetadataInfo::FETCH_EAGER)
            ->getResult();
    }

    /**
     * @return FlightInformation[]
     */
    public function getItemsWithRightEngineParametersByAirplaneNumber(int $airplaneNumber): array
    {
        return $this->entityManager
            ->createQuery('SELECT f FROM App\Entity\FlightInformation\FlightInformation f WHERE f.flightInformationId.airplaneNumber = :airplaneNumber ORDER BY f.flightInformationId.flightDate')
            ->setParameter('airplaneNumber', $airplaneNumber)
            ->setFetchMode(FlightInformation::class, 'rightEngineParameters', ClassMetadataInfo::FETCH_EAGER)
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