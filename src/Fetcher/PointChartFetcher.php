<?php

declare(strict_types=1);

namespace App\Fetcher;

use Doctrine\ORM\EntityManagerInterface;

class PointChartFetcher
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function findBySlug(string $slug): array
    {
        return $this->entityManager
            ->createQuery('SELECT p FROM App\Entity\FlightInformation\FlightInformationPoint p, App\Entity\FlightInformation\FlightInformation f WHERE f.slug = :slug AND p MEMBER OF f.points')
            ->setParameter('slug', $slug)
            ->getResult();
    }
}