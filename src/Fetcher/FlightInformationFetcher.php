<?php

declare(strict_types=1);

namespace App\Fetcher;

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
            ->createQuery('SELECT count(f) FROM App\Entity\FlightInformation f')
            ->getSingleScalarResult();
    }

    public function items(int $offset, int $limit): array
    {
        return $this->entityManager
            ->createQuery('SELECT f FROM App\Entity\FlightInformation f ORDER BY f.id.airplane, f.id.date, f.id.departure')
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->getResult();
    }

    /**
     * @return \App\Entity\FlightInformation[]
     */
    public function findByAirplane(int $airplane): array
    {
        return $this->entityManager
            ->createQuery('SELECT f FROM App\Entity\FlightInformation f WHERE f.id.airplane = :airplane')
            ->setParameter('airplane', $airplane)
            ->getResult();
    }
}