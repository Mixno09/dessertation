<?php

declare(strict_types=1);

namespace App\Module\Dessertation\Domain\FlightInformation;

interface FlightInformationRepositoryInterface
{
    public function find(FlightInformationId $id): ?FlightInformation;

    public function save(FlightInformation $flightInformation): void;
}