<?php

declare(strict_types=1);

namespace App\Domain;

class ChangeAlfaRUD
{
    private array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function stopTime(): int
    {

    }
}