<?php

declare(strict_types=1);

namespace App\Domain;

use App\Services\MathService;

class ChangeAlfaRUD
{
    private array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function stopTime(): int
    {
        $alfaRud = [];
        foreach ($this->data as $information) {
            $time = $information['time'];
            if (array_key_exists('alfa_rud_left', $information)) {
                $alfaRud[$time] = $information['alfa_rud_left'];
            } else {
                $alfaRud[$time] = $information['alfa_rud_right'];
            }
        }
        $filterAlfaRud = array_reverse(MathService::filter($alfaRud), true);
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