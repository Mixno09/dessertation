<?php

declare(strict_types=1);

namespace App\Entity;

class AirplaneId
{
    private int $airplane;

    public function __construct(int $airplane)
    {
        $this->airplane = $airplane;
    }

    public function getAirplane(): int
    {
        return $this->airplane;
    }
}