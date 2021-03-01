<?php

declare(strict_types=1);

namespace App\Fetcher;

use App\ViewModel\RunOutList\Airplane;
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