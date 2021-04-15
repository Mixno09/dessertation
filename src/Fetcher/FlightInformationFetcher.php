<?php

declare(strict_types=1);

namespace App\Fetcher;

use App\Entity\FlightInformation\EngineParameter;
use App\Entity\FlightInformation\FlightInformation;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;

class FlightInformationFetcher
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function hasFlightInformation(int $airplaneNumber, DateTimeImmutable $flightDate, int $flightNumber): bool
    {
        $flightInformation = $this->entityManager->createQuery('SELECT f FROM App\Entity\FlightInformation\FlightInformation f WHERE f.flightInformationId.airplaneNumber = :airplaneNumber AND f.flightInformationId.flightDate = :flightDate AND f.flightInformationId.flightNumber = :flightNumber')
            ->setParameter('airplaneNumber', $airplaneNumber)
            ->setParameter('flightDate', $flightDate)
            ->setParameter('flightNumber', $flightNumber)
            ->getOneOrNullResult();

        return ($flightInformation instanceof FlightInformation);
    }

    public function count(): int
    {
        return (int) $this->entityManager
            ->createQuery('SELECT count(f) FROM App\Entity\FlightInformation\FlightInformation f')
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
     * @return EngineParameter[]
     */
    public function getLeftEngineParametersBySlug(string $slug): array
    {
        return $this->entityManager
            ->createQuery('SELECT ep FROM App\Entity\FlightInformation\EngineParameter ep, App\Entity\FlightInformation\FlightInformation f JOIN f.leftEngineParameters epc WHERE f.slug = :slug AND ep MEMBER OF epc.collection')
            ->setParameter('slug', $slug)
            ->getResult();
    }

    /**
     * @return EngineParameter[]
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
}