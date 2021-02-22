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
            ->createQuery('SELECT COUNT(DISTINCT f.id.airplane) FROM App\Entity\FlightInformation\FlightInformation f')
            ->getSingleScalarResult();
    }

    public function items(int $offset, int $limit): array
    {
        $rows = $this->entityManager
            ->createQuery('SELECT DISTINCT f.id.airplane FROM App\Entity\FlightInformation\FlightInformation f ORDER BY f.id.airplane')
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->getArrayResult();

        $items = [];
        foreach ($rows as $row) {
            $item = new Airplane();
            $item->id = $row['id.airplane'];
            $items[] = $item;
        }

        return $items;
    }
}