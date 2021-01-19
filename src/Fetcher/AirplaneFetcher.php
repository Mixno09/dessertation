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

    public function count(): int
    {
        return (int) $this->entityManager
            ->createQuery('SELECT DISTINCT count(f.id.airplane) FROM App\Entity\FlightInformation f ')
            ->getSingleScalarResult();
    }

    public function items(int $offset, int $limit): array
    {
        return $this->entityManager
            ->createQuery('SELECT DISTINCT f.id.airplane FROM App\Entity\FlightInformation f ORDER BY f.id.airplane')
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->getResult();
    }
}