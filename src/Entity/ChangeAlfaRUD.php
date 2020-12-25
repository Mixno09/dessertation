<?php

declare(strict_types=1);

namespace App\Entity;

use App\Service\MathService;

class ChangeAlfaRUD
{
    private array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function stopTime(): int
    {
        $filterAlfaRud = array_reverse(MathService::filter($this->data), true);
        $key = '';
        foreach ($filterAlfaRud as $key => $value) {
            if ((float) $filterAlfaRud[$key] + 2 < ((float) $filterAlfaRud[$key - 1])) {
                break;
            }
            continue;
        }
        return $key;
    }
}