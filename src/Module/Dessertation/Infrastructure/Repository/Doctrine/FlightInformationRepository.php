<?php

declare(strict_types=1);

namespace App\Module\Dessertation\Infrastructure\Repository\Doctrine;

use App\Module\Dessertation\Domain\FlightInformation\FlightInformation;
use App\Module\Dessertation\Domain\FlightInformation\FlightInformationId;
use App\Module\Dessertation\Domain\FlightInformation\FlightInformationRepositoryInterface;

final class FlightInformationRepository implements FlightInformationRepositoryInterface
{

    public function find(FlightInformationId $id): ?FlightInformation
    {
        // TODO: Implement find() method.
    }

    public function save(FlightInformation $flightInformation): void
    {
        // TODO: Implement save() method.
    }
}