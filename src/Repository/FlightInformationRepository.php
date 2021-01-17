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

    public function findByAirplane(int $airplane): array
    {
//        $dql = 'SELECT f.runOutRotor FROM App\Entity\FlightInformation f WHERE f.id.airplane = :airplane'; // todo можно ли извлечь embedded из flightInformation??
        $dql = 'SELECT f FROM App\Entity\FlightInformation f WHERE f.id.airplane = :airplane';
        return $this->entityManager
            ->createQuery($dql)
            ->setParameter(':airplane', $airplane)
            ->getArrayResult();
//        return $repository->findBy(['id.airplane' => $airplane]);
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