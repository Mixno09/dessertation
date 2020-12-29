<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\FlightInformation;
use App\Entity\FlightInformationId;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class FlightInformationRepository
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function find(FlightInformationId $id): ?FlightInformation //todo сделать исключение
    {
        $repository = $this->getRepository();
        /** @var FlightInformation|null $flightInformation */
        $flightInformation = $repository->findOneBy([
            'id.airplane' => $id->getAirplane(),
            'id.date' => $id->getDate(),
            'id.departure' => $id->getDeparture(),
        ]);

        return $flightInformation;
    }

    public function findBySlug(string $slug): ?FlightInformation //todo сделать исключение
    {
        $repository = $this->getRepository();
        /** @var FlightInformation|null $flightInformation */
        $flightInformation = $repository->findOneBy([
            'slug' => $slug,
        ]);
        return $flightInformation;
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

    public function save(FlightInformation $flightInformation): void
    {
        $this->entityManager->persist($flightInformation);
        $this->entityManager->flush(); //todo заменить на транзакцию
    }

    public function delete(FlightInformation $flightInformation): void
    {
        $this->entityManager->remove($flightInformation);
        $this->entityManager->flush(); //todo заменить на транзакцию
    }

    private function getRepository(): EntityRepository
    {
        /** @var EntityRepository $repository */
        $repository = $this->entityManager->getRepository(FlightInformation::class);
        return $repository;
    }
}