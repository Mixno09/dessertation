<?php

declare(strict_types=1);

namespace App\Entity\FlightInformation;

class MutualParameter
{
    private ?int $id;
    private int $time;
    private float $distributionDensityT4Rnd;
    private float $distributionDensityT4Rvd;
    private float $distributionDensityRndRvd;

    public function __construct(
        int $time,
        float $distributionDensityT4Rnd,
        float $distributionDensityT4Rvd,
        float $distributionDensityRndRvd
    ) {
        $this->time = $time;
        $this->distributionDensityT4Rnd = round($distributionDensityT4Rnd, 9);
        $this->distributionDensityT4Rvd = round($distributionDensityT4Rvd, 9);
        $this->distributionDensityRndRvd = round($distributionDensityRndRvd, 9);
    }

    public function getTime(): int
    {
        return $this->time;
    }

    public function getDistributionDensityT4Rnd(): float
    {
        return $this->distributionDensityT4Rnd;
    }

    public function getDistributionDensityT4Rvd(): float
    {
        return $this->distributionDensityT4Rvd;
    }

    public function getDistributionDensityRndRvd(): float
    {
        return $this->distributionDensityRndRvd;
    }
}