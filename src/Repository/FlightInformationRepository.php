<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\FlightInformation;
use App\Entity\FlightInformationId;
use Doctrine\ORM\EntityManagerInterface;

class FlightInformationRepository
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function find(FlightInformationId $id): ?FlightInformation
    {
        $repository = $this->entityManager->getRepository(FlightInformation::class);
        /** @var FlightInformation|null $flightInformation */
        $flightInformation = $repository->findOneBy([
            'id.airplane' => $id->getAirplane(),
            'id.date' => $id->getDate(),
            'id.departure' => $id->getDeparture(),
        ]);

        return $flightInformation;
    }

    public function save(FlightInformation $flightInformation): void
    {
        $this->entityManager->persist($flightInformation);
        $this->entityManager->flush();
    }
}