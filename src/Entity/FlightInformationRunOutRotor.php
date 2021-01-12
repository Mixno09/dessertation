<?php

declare(strict_types=1);

namespace App\Entity;

use App\Service\MathService;

class FlightInformationRunOutRotor
{
    private $primaryKey;
    private float $rndLeftRaw;
    private float $rndRightRaw;
    private float $rvdLeftRaw;
    private float $rvdRightRaw;
    private float $rndLeftCalc;
    private float $rndRightCalc;
    private float $rvdLeftCalc;
    private float $rvdRightCalc;

    /**
     * @param FlightInformationPoint[] $points
     */
    public function __construct(array $points)
    {
        $this->setRndLeft(...$points);
        $this->setRndRight(...$points);
        $this->setRvdLeft(...$points);
        $this->setRvdRight(...$points);
    }

    private function setRndLeft(FlightInformationPoint ...$points): void
    {
        [$this->rndLeftRaw, $this->rndLeftCalc] = $this->calcRunOutValues(
            $points,
            fn(FlightInformationPoint $point) => [$point->getTime() => $point->getAlfaRUDLeft()],
            fn(FlightInformationPoint $point) => [$point->getTime() => $point->getRndLeft()]
        );
    }

    private function setRndRight(FlightInformationPoint ...$points): void
    {
        [$this->rndRightRaw, $this->rndRightCalc] = $this->calcRunOutValues(
            $points,
            fn(FlightInformationPoint $point) => [$point->getTime() => $point->getAlfaRUDRight()],
            fn(FlightInformationPoint $point) => [$point->getTime() => $point->getRndRight()]
        );
    }

    private function setRvdLeft(FlightInformationPoint ...$points): void
    {
        [$this->rvdLeftRaw, $this->rvdLeftCalc] = $this->calcRunOutValues(
            $points,
            fn(FlightInformationPoint $point) => [$point->getTime() => $point->getAlfaRUDLeft()],
            fn(FlightInformationPoint $point) => [$point->getTime() => $point->getRvdLeft()]
        );
    }

    private function setRvdRight(FlightInformationPoint ...$points): void
    {
        [$this->rvdRightRaw, $this->rvdRightCalc] = $this->calcRunOutValues(
            $points,
            fn(FlightInformationPoint $point) => [$point->getTime() => $point->getAlfaRUDRight()],
            fn(FlightInformationPoint $point) => [$point->getTime() => $point->getRvdRight()]
        );
    }

    /**
     * @param FlightInformationPoint[] $points
     */
    private function calcRunOutValues(array $points, callable $rudPointCallback, callable $enginePointCallback): array
    {
        $rudPoints = $this->arrayMapWithKey($rudPointCallback, $points);
        $stopTime = $this->findStopTime($rudPoints);

        $enginePoints = $this->arrayMapWithKey($enginePointCallback, $points);
        $engineRangePoints = $this->findEngineRange($stopTime, $enginePoints);
        
        $rawValue = $this->calcRawValue($engineRangePoints);
        $calcValue = $this->calcApproximateValue($engineRangePoints);

        return [$rawValue, $calcValue];
    }

    private function findStopTime(array $rudPoints): int
    {
        $filterAlfaRud = array_reverse(MathService::filter($rudPoints), true);
        $stopTime = -1;
        foreach ($filterAlfaRud as $stopTime => $value) {
            if ((float) $filterAlfaRud[$stopTime] + 2 < ((float) $filterAlfaRud[$stopTime - 1])) {
                break;
            }
            continue;
        }
        return $stopTime;
    }

    private function findEngineRange(int $stopTime, array $enginePoints): array
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

    private function calcRawValue(array $engineRangePoints): float
    {
        if (count($engineRangePoints) < 2) {
            return -1;
        }
        return array_key_last($engineRangePoints) - array_key_first($engineRangePoints);
    }

    private function calcApproximateValue(array $engineRangePoints): float
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

    private function arrayMapWithKey(callable $callback, array $data): array
    {
        $result = [];
        foreach ($data as $value) {
            $value = call_user_func($callback, $value);
            $result = array_merge($result, $value);
        }
        return $result;
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