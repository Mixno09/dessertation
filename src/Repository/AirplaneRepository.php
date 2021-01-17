<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Airplane;
use App\Entity\AirplaneId;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Exception;

class AirplaneRepository
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function find(AirplaneId $id): ?Airplane //todo реализовать
    {
        $repository = $this->getRepository();
        /** @var ?Airplane $airplane */
        $airplane = $repository->findOneBy(['id.airplane' => $id->getAirplane()]);
        if ($airplane instanceof Airplane) {
            throw new Exception('Борт с номером ' . $id->getAirplane() . ' уже существует в базе данных');
        }

        return $airplane;
    }

    public function save(Airplane $airplane): void
    {
        $this->entityManager->persist($airplane);
        $this->entityManager->flush();
    }

    private function getRepository(): EntityRepository
    {
        /** @var EntityRepository $repository */
        $repository = $this->entityManager->getRepository(Airplane::class);
        return $repository;
    }
}