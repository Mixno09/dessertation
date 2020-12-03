<?php

declare(strict_types=1);

namespace App\Domain;

class ChangeRevs
{
    private array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function stopRevs(int $stopTime): int
    {

    }

    public function approximation(int $stopTime, int $stopRevs): float
    {

    }
}