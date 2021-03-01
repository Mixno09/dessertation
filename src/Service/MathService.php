<?php

declare(strict_types=1);

namespace App\Service;

class MathService
{
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
        $count = count($data);
        for ($key = 0; $key < $count; $key++) {
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