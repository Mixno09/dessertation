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

    public function hasOneByFlightInformationId(int $airplaneNumber, DateTimeImmutable $flightDate, int $flightNumber): bool
    {
        $flightInformation = $this->entityManager
            ->createQuery('SELECT f FROM App\Entity\FlightInformation\FlightInformation f WHERE f.flightInformationId.airplaneNumber = :airplaneNumber AND f.flightInformationId.flightDate = :flightDate AND f.flightInformationId.flightNumber = :flightNumber')
            ->setParameter('airplaneNumber', $airplaneNumber)
            ->setParameter('flightDate', $flightDate->format('Y-m-d'))
            ->setParameter('flightNumber', $flightNumber)
            ->getOneOrNullResult();

        return ($flightInformation instanceof FlightInformation);
    }

    public function findOneByFlightInformationId(int $airplaneNumber, DateTimeImmutable $flightDate, int $flightNumber): ?FlightInformation
    {
        return $this->entityManager
            ->createQuery('SELECT f FROM App\Entity\FlightInformation\FlightInformation f WHERE f.flightInformationId.airplaneNumber = :airplaneNumber AND f.flightInformationId.flightDate = :flightDate AND f.flightInformationId.flightNumber = :flightNumber')
            ->setParameter('airplaneNumber', $airplaneNumber)
            ->setParameter('flightDate', $flightDate->format('Y-m-d'))
            ->setParameter('flightNumber', $flightNumber)
            ->getOneOrNullResult();
    }

    public function count(): int
    {
        return (int) $this->entityManager
            ->createQuery('SELECT count(f) FROM App\Entity\FlightInformation\FlightInformation f')
            ->getSingleScalarResult();
    }

    public function getCountUniqueAirplane(): int
    {
        return (int) $this->entityManager
            ->createQuery('SELECT COUNT(DISTINCT f.flightInformationId.airplaneNumber) FROM App\Entity\FlightInformation\FlightInformation f')
            ->getSingleScalarResult();
    }

    /**
     * @return FlightInformation[]
     */
    public function items(int $offset, int $limit): array
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
    public function itemsAirplane(int $offset, int $limit): array
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
    public function getLeftEngineParametersBySlug(string $slug): array
    {
        return $this->entityManager
            ->createQuery('SELECT ep FROM App\Entity\FlightInformation\EngineParameter ep, App\Entity\FlightInformation\FlightInformation f JOIN f.leftEngineParameters epc WHERE f.slug = :slug AND ep MEMBER OF epc.collection')
            ->setParameter('slug', $slug)
            ->getResult();
    }

    /**
     * @return \App\Entity\FlightInformation\EngineParameter[]
     */
    public function getRightEngineParametersBySlug(string $slug): array
    {
        return $this->entityManager
            ->createQuery('SELECT ep FROM App\Entity\FlightInformation\EngineParameter ep, App\Entity\FlightInformation\FlightInformation f JOIN f.rightEngineParameters epc WHERE f.slug = :slug AND ep MEMBER OF epc.collection')
            ->setParameter('slug', $slug)
            ->getResult();
    }

    public function findBySlug(string $slug): ?FlightInformation
    {
        return $this->entityManager
            ->createQuery('SELECT f FROM App\Entity\FlightInformation\FlightInformation f WHERE f.slug = :slug')
            ->setParameter('slug', $slug)
            ->getOneOrNullResult();
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
}