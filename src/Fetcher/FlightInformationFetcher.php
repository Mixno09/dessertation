<?php

declare(strict_types=1);

namespace App\Fetcher;

use App\Entity\FlightInformation;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class FlightInformationFetcher
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function count(): int
    {
        $repository = $this->getRepository();
        return (int) $repository
            ->createQueryBuilder('f') //todo заменить на dql
            ->select('count(f.primaryKey)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function items(int $offset, int $limit): array
    {
        $repository = $this->getRepository();
        return $repository //todo заменить на dql
        ->createQueryBuilder('f')
            ->select()
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->addOrderBy('f.id.airplane')
            ->addOrderBy('f.id.date')
            ->addOrderBy('f.id.departure')
            ->getQuery()
            ->getResult();
    }

    private function getRepository(): EntityRepository
    {
        /** @var EntityRepository $repository */
        $repository = $this->entityManager->getRepository(FlightInformation::class);
        return $repository;
    }
}