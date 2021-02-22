<?php

declare(strict_types=1);

namespace App\Entity\FlightInformation;

class AverageEngineParameter
{
    private float $t4;
    private float $rnd;
    private float $rvd;

    public function __construct(float $t4, float $rnd, float $rvd)
    {
        $this->t4 = $t4;
        $this->rnd = $rnd;
        $this->rvd = $rvd;
    }
}