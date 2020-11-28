<?php

declare(strict_types=1);

namespace App\Services;

class MathService
{
    public function getApproximationTimeRotor(array $value): ?float
    {
        $n = count($value);
        if ($n <= 0) {
            return null;
        }

        $sumXLnY = 0;
        $sumX = 0;
        $sumLnY = 0;
        $sumX2 = 0;
        foreach ($value as $x => $y) {
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