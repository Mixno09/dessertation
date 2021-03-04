<?php

declare(strict_types=1);

namespace App\Fetcher;

use App\Entity\FlightInformation\EngineParameter;
use App\Entity\FlightInformation\FlightInformation;
use Doctrine\ORM\EntityManagerInterface;

class FlightInformationFetcher
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
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

    public function findByAirplane(int $airplane): array
    {
        return $this->entityManager
            ->createQuery('SELECT f FROM App\Entity\FlightInformation\FlightInformation f WHERE f.id.airplane = :airplane')
            ->setParameter('airplane', $airplane)
            ->getResult();
    }
}