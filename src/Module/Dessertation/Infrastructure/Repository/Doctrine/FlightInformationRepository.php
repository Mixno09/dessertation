<?php

declare(strict_types=1);

namespace App\Module\Dessertation\Infrastructure\Repository\Doctrine;

use App\Module\Dessertation\Domain\FlightInformation\FlightInformation;
use App\Module\Dessertation\Domain\FlightInformation\FlightInformationId;
use App\Module\Dessertation\Domain\FlightInformation\FlightInformationRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

final class FlightInformationRepository implements FlightInformationRepositoryInterface
{
    private EntityRepository $repository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        /** @noinspection PhpFieldAssignmentTypeMismatchInspection */
        $this->repository = $entityManager->getRepository(FlightInformation::class);
    }

    public function find(FlightInformationId $id): ?FlightInformation
    {
        return $this->repository->findOneBy([
            'id.airplane' => $id->getAirplane(),
            'id.date' => $id->getDate(),
            'id.departure' => $id->getDeparture(),
        ]);
    }

    public function save(FlightInformation $flightInformation): void
    {
        // TODO: Implement save() method.
    }
}