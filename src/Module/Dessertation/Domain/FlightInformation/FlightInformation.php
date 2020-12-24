<?php

declare(strict_types=1);

namespace App\Module\Dessertation\Domain\FlightInformation;

class FlightInformation
{
    private $primaryKey;
    private FlightInformationId $id;
    /**
     * @var FlightInformationDataPoint[]
     */
    private array $points;

    public function __construct(FlightInformationId $id, array $points)
    {
        $this->setId($id);
        $this->setPoints(...$points);
    }

    public function getPoints(): array
    {
        return $this->points;
    }

    private function setPoints(FlightInformationDataPoint ...$points): void
    {
        $this->points = $points;
    }

    private function setId(FlightInformationId $id): void
    {
        $this->id = $id;
    }

    public function getId(): FlightInformationId
    {
        return $this->id;
    }
}