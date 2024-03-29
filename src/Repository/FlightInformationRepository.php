<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\FlightInformation\FlightInformation;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadataInfo;

class FlightInformationRepository
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function findFlightInformationByFlightInformationId(int $airplaneNumber, DateTimeImmutable $flightDate, int $flightNumber): ?FlightInformation
    {
        return $this->entityManager
            ->createQuery('SELECT f FROM App\Entity\FlightInformation\FlightInformation f WHERE f.flightInformationId.airplaneNumber = :airplaneNumber AND f.flightInformationId.flightDate = :flightDate AND f.flightInformationId.flightNumber = :flightNumber')
            ->setParameter('airplaneNumber', $airplaneNumber)
            ->setParameter('flightDate', $flightDate->format('Y-m-d'))
            ->setParameter('flightNumber', $flightNumber)
            ->getOneOrNullResult();
    }

    public function countFlightInformation(): int
    {
        return (int) $this->entityManager
            ->createQuery('SELECT count(f) FROM App\Entity\FlightInformation\FlightInformation f')
            ->getSingleScalarResult();
    }

    public function countAirplaneNumber(): int
    {
        return (int) $this->entityManager
            ->createQuery('SELECT COUNT(DISTINCT f.flightInformationId.airplaneNumber) FROM App\Entity\FlightInformation\FlightInformation f')
            ->getSingleScalarResult();
    }

    /**
     * @return FlightInformation[]
     */
    public function findFlightInformationInterval(int $offset, int $limit): array
    {
        return $this->entityManager
            ->createQuery('SELECT f FROM App\Entity\FlightInformation\FlightInformation f ORDER BY f.flightInformationId.airplaneNumber, f.flightInformationId.flightDate, f.flightInformationId.flightNumber')
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->getResult();
    }

    /**
     * @return int[]
     */
    public function findAirplaneNumberInterval(int $offset, int $limit): array
    {
        $rows = $this->entityManager
            ->createQuery('SELECT DISTINCT f.flightInformationId.airplaneNumber FROM App\Entity\FlightInformation\FlightInformation f ORDER BY f.flightInformationId.airplaneNumber')
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->getArrayResult();

        return array_column($rows, 'flightInformationId.airplaneNumber');
    }

    /**
     * @return \App\Entity\FlightInformation\EngineParameter[]
     */
    public function findLeftEngineParametersBySlug(string $slug): array
    {
        return $this->entityManager
            ->createQuery('SELECT ep FROM App\Entity\FlightInformation\EngineParameter ep, App\Entity\FlightInformation\FlightInformation f JOIN f.leftEngineParameters epc WHERE f.slug = :slug AND ep MEMBER OF epc.collection')
            ->setParameter('slug', $slug)
            ->getResult();
    }

    /**
     * @return \App\Entity\FlightInformation\EngineParameter[]
     */
    public function findRightEngineParametersBySlug(string $slug): array
    {
        return $this->entityManager
            ->createQuery('SELECT ep FROM App\Entity\FlightInformation\EngineParameter ep, App\Entity\FlightInformation\FlightInformation f JOIN f.rightEngineParameters epc WHERE f.slug = :slug AND ep MEMBER OF epc.collection')
            ->setParameter('slug', $slug)
            ->getResult();
    }

    public function findFlightInformationBySlug(string $slug): ?FlightInformation
    {
        return $this->entityManager
            ->createQuery('SELECT f FROM App\Entity\FlightInformation\FlightInformation f WHERE f.slug = :slug')
            ->setParameter('slug', $slug)
            ->getOneOrNullResult();
    }

    /**
     * @return FlightInformation[]
     */
    public function findFlightInformationByAirplaneNumberWithEngineParameter(int $airplaneNumber): array
    {
        return $this->entityManager
            ->createQuery('SELECT f FROM App\Entity\FlightInformation\FlightInformation f WHERE f.flightInformationId.airplaneNumber = :airplaneNumber ORDER BY f.flightInformationId.flightDate, f.flightInformationId.flightNumber')
            ->setParameter('airplaneNumber', $airplaneNumber)
            ->setFetchMode(FlightInformation::class, 'leftEngineParameters', ClassMetadataInfo::FETCH_EAGER)
            ->setFetchMode(FlightInformation::class, 'rightEngineParameters', ClassMetadataInfo::FETCH_EAGER)
            ->getResult();
    }

    public function findLeftMutualParameterWithEngineParameterBySlug(string $slug): array
    {
        return $this->entityManager
            ->createQuery('SELECT mp, ep.t4 AS t4, ep.rnd AS rnd, ep.rvd AS rvd FROM App\Entity\FlightInformation\MutualParameter mp, App\Entity\FlightInformation\EngineParameter ep, App\Entity\FlightInformation\FlightInformation f JOIN f.leftEngineParameters epc WHERE f.slug = :slug AND mp MEMBER OF epc.mutualParameters AND ep MEMBER OF epc.collection AND mp.time = ep.time')
            ->setParameter('slug', $slug)
            ->getResult();
    }
}