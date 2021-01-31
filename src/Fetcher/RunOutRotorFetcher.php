<?php

declare(strict_types=1);

namespace App\Fetcher;

use Doctrine\ORM\EntityManagerInterface;

class RunOutRotorFetcher
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function findByAirplane(int $airplane): array
    {
        return $this->entityManager
            ->createQuery('SELECT f FROM App\Entity\FlightInformation f WHERE f.id.airplane = :airplane')
            ->setParameter('airplane', $airplane)
            ->getArrayResult();
    }
}