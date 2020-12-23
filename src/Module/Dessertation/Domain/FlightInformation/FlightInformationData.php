<?php

declare(strict_types=1);

namespace App\Module\Dessertation\Domain\FlightInformation;

class FlightInformationData
{
    private int $primaryKey;
    /**
     * @var FlightInformationDataPoint[]
     */
    private array $points;

    public function __construct(array $points)
    {
        $this->setPoints(...$points);
    }

    private function setPoints(FlightInformationDataPoint ...$points): void
    {
        $this->points = $points;
    }
}