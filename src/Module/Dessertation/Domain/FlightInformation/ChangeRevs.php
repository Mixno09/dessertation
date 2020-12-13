<?php

declare(strict_types=1);

namespace App\Module\Dessertation\Domain\FlightInformation;

class ChangeRevs
{
    private array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function stopRevs(int $stopTime): int
    {
        $engineRevs = array_slice($this->data, $stopTime, null, true);
        $key = '';
        foreach ($engineRevs as $key => $value) {
            if ($engineRevs[$key] >= 11) {
                continue;
            }
            break;
        }
        return $key;
    }

    public function approximation(int $stopTime, int $stopRevs): ?float
    {
        if ($this->data <= 0) {
            return null;
        }

        $length = $stopRevs - $stopTime;
        $engineRevs = array_slice($this->data, $stopTime, $length);

        $n = count($engineRevs);
        $sumXLnY = 0;
        $sumX = 0;
        $sumLnY = 0;
        $sumX2 = 0;
        foreach ($engineRevs as $x => $y) {
            $y = (float) $y;
            $sumXLnY += $x * log($y);
            $sumX += $x;
            $sumLnY += log($y);
            $sumX2 += $x ** 2;
        }

        $b = ($n * $sumXLnY - $sumX * $sumLnY) / ($n * $sumX2 - $sumX ** 2);
        $a = exp(($sumLnY - $b * $sumX) / $n);
        return log(0.2 / $a) / $b;
    }
}