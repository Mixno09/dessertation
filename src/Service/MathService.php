<?php

declare(strict_types=1);

namespace App\Service;

class MathService
{
    public function approximationTimeRotor(array $value): ?float
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

    private static function medianFilter(...$values)
    {
        sort($values);
        if (count($values) % 2 === 0) {
            $rightIndex = count($values) / 2;
            $leftIndex = $rightIndex - 1;
            $right = $values[$rightIndex];
            $left = $values[$leftIndex];
            $result = ($right + $left) / 2;
        } else {
            $lastIndex = count($values) - 1;
            $middleIndex = $lastIndex / 2;
            $result = $values[$middleIndex];
        }
        return $result;
    }

    public static function filter(array $data): array
    {
        $result = [];
        for ($key = 0; $key < count($data); $key++) {
            $values = [];
            $offset = 5;
            $leftIndex = $key - $offset;
            while ($leftIndex < 0) {
                $values[] = $data[$key];
                $leftIndex++;
            }
            $rightIndex = $key + $offset;
            while ($rightIndex >= count($data)) {
                $values[] = $data[$key];
                $rightIndex--;
            }
            for ($i = $leftIndex; $i <= $rightIndex; $i++) {
                $values[] = $data[$i];
            }
            $middle = self::medianFilter(...$values);
            $result[$key] = $middle;
        }
        return $result;
    }
}