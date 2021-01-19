<?php

declare(strict_types=1);

namespace App\Entity;

use App\Service\MathService;

class FlightInformationRunOutRotor
{
    private float $rndLeftRaw;
    private float $rndRightRaw;
    private float $rvdLeftRaw;
    private float $rvdRightRaw;
    private float $rndLeftCalc;
    private float $rndRightCalc;
    private float $rvdLeftCalc;
    private float $rvdRightCalc;

    public function __construct(
        float $rndLeftRaw,
        float $rndRightRaw,
        float $rvdLeftRaw,
        float $rvdRightRaw,
        float $rndLeftCalc,
        float $rndRightCalc,
        float $rvdLeftCalc,
        float $rvdRightCalc
    ) {
        $this->rndLeftRaw = $rndLeftRaw;
        $this->rndRightRaw = $rndRightRaw;
        $this->rvdLeftRaw = $rvdLeftRaw;
        $this->rvdRightRaw = $rvdRightRaw;
        $this->rndLeftCalc = $rndLeftCalc;
        $this->rndRightCalc = $rndRightCalc;
        $this->rvdLeftCalc = $rvdLeftCalc;
        $this->rvdRightCalc = $rvdRightCalc;
    }

    /**
     * @param FlightInformationPoint[] $points
     */
    public static function fromPoints(array $points): self
    {
        $alfaRUDLeft = [];
        $alfaRUDRight = [];
        $revsRndLeft = [];
        $revsRndRight = [];
        $revsRvdLeft = [];
        $revsRvdRight = [];
        foreach ($points as $point) {
            $time = $point->getTime();
            $alfaRUDLeft[$time] = $point->getAlfaRUDLeft();
            $alfaRUDRight[$time] = $point->getAlfaRUDRight();
            $revsRndLeft[$time] = $point->getRndLeft();
            $revsRndRight[$time] = $point->getRndRight();
            $revsRvdLeft[$time] = $point->getRvdLeft();
            $revsRvdRight[$time] = $point->getRvdRight();
        }
        [$rndLeftRaw, $rndLeftCalc] = self::calcRunOutValues($alfaRUDLeft, $revsRndLeft);
        [$rndRightRaw, $rndRightCalc] = self::calcRunOutValues($alfaRUDRight, $revsRndRight);
        [$rvdLeftRaw, $rvdLeftCalc] = self::calcRunOutValues($alfaRUDLeft, $revsRvdLeft);
        [$rvdRightRaw, $rvdRightCalc] = self::calcRunOutValues($alfaRUDRight, $revsRvdRight);

        return new self(
            $rndLeftRaw,
            $rndRightRaw,
            $rvdLeftRaw,
            $rvdRightRaw,
            $rndLeftCalc,
            $rndRightCalc,
            $rvdLeftCalc,
            $rvdRightCalc
        );
    }

    private static function calcRunOutValues(array $alfaRUD, array $revs): array
    {
        $stopTime = self::findStopTime($alfaRUD);

        $engineRangePoints = self::findEngineRange($stopTime, $revs);

        $rawValue = self::calcRawValue($engineRangePoints);
        $calcValue = self::calcApproximateValue($engineRangePoints);

        return [$rawValue, $calcValue];
    }

    private static function findStopTime(array $rudPoints): int
    {
        $filterAlfaRud = array_reverse(MathService::filter($rudPoints), true);
        $stopTime = -1;
        foreach ($filterAlfaRud as $stopTime => $value) {
            if ((float)$filterAlfaRud[$stopTime] + 2 < ((float)$filterAlfaRud[$stopTime - 1])) {
                break;
            }
            continue;
        }
        return $stopTime;
    }

    private static function findEngineRange(int $stopTime, array $enginePoints): array
    {
        if ($stopTime < 0) {
            return [];
        }

        $engineRevs = array_slice($enginePoints, $stopTime, null, true);
        $stopRotor = -1;
        foreach ($engineRevs as $stopRotor => $value) {
            if ($engineRevs[$stopRotor] >= 11) {
                continue;
            }
            break;
        }

        return array_filter(
            $enginePoints,
            fn(int $time) => ($time >= $stopTime && $time <= $stopRotor),
            ARRAY_FILTER_USE_KEY
        );
    }

    private static function calcRawValue(array $engineRangePoints): float
    {
        if (count($engineRangePoints) < 2) {
            return -1;
        }
        return array_key_last($engineRangePoints) - array_key_first($engineRangePoints);
    }

    private static function calcApproximateValue(array $engineRangePoints): float
    {
        $engineRangePoints = array_values($engineRangePoints);

        if (count($engineRangePoints) < 2) {
            return -1;
        }

        $n = count($engineRangePoints);
        $sumXLnY = 0;
        $sumX = 0;
        $sumLnY = 0;
        $sumX2 = 0;
        foreach ($engineRangePoints as $x => $y) {
            $y = (float)$y;
            $sumXLnY += $x * log($y);
            $sumX += $x;
            $sumLnY += log($y);
            $sumX2 += $x ** 2;
        }

        $b = ($n * $sumXLnY - $sumX * $sumLnY) / ($n * $sumX2 - $sumX ** 2);
        $a = exp(($sumLnY - $b * $sumX) / $n);
        return log(0.2 / $a) / $b;
    }

    public function getRndLeftRaw(): float
    {
        return $this->rndLeftRaw;
    }

    public function getRndRightRaw(): float
    {
        return $this->rndRightRaw;
    }

    public function getRvdLeftRaw(): float
    {
        return $this->rvdLeftRaw;
    }

    public function getRvdRightRaw(): float
    {
        return $this->rvdRightRaw;
    }

    public function getRndLeftCalc(): float
    {
        return $this->rndLeftCalc;
    }

    public function getRndRightCalc(): float
    {
        return $this->rndRightCalc;
    }

    public function getRvdLeftCalc(): float
    {
        return $this->rvdLeftCalc;
    }

    public function getRvdRightCalc(): float
    {
        return $this->rvdRightCalc;
    }
}