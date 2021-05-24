<?php

declare(strict_types=1);

namespace App\Entity\FlightInformation;

use DateTimeImmutable;

class FlightInformationFactory
{
    public static function create(
        int $airplaneNumber,
        DateTimeImmutable $flightDate,
        int $flightNumber,
        array $time,
        array $t4Right,
        array $t4Left,
        array $alfaRudLeft,
        array $alfaRudRight,
        array $rndLeft,
        array $rvdLeft,
        array $rndRight,
        array $rvdRight
    ): FlightInformation {
        $flightInformationId = new FlightInformationId(
            $airplaneNumber,
            $flightDate,
            $flightNumber
        );

        $leftParameters = self::calcParameters($time, $t4Left, $rndLeft, $rvdLeft);
        $rightParameters = self::calcParameters($time, $t4Right, $rndRight, $rvdRight);
        $leftEngineParameterCollection = self::createEngineParameterCollection($time, $t4Left, $alfaRudLeft, $rndLeft, $rvdLeft, $leftParameters);
        $rightEngineParameterCollection = self::createEngineParameterCollection($time, $t4Right, $alfaRudRight, $rndRight, $rvdRight, $rightParameters);
        $slug = implode('_', [
            $flightInformationId->getAirplaneNumber(),
            $flightInformationId->getFlightDate()->format('Y-m-d'),
            $flightInformationId->getFlightNumber()
        ]);

        return new FlightInformation(
            $flightInformationId,
            $leftEngineParameterCollection,
            $rightEngineParameterCollection,
            $slug
        );
    }

    private static function calcParameters(array $time, array $t4, array $rnd, array $rvd): ?array
    {
        $timeMode = [];
        $t4Mode = [];
        $rndMode = [];
        $rvdMode = [];
        foreach ($time as $value) {
            $parameterRnd = $rnd[$value];
            if ($parameterRnd > 31 && $parameterRnd < 36.5) {
                $timeMode[] = $value;
                $t4Mode[$value] = $t4[$value];
                $rndMode[$value] = $rnd[$value];
                $rvdMode[$value] = $rvd[$value];
            }
        }
        if (count($t4Mode) === 0 || count($rndMode) === 0 || count($rvdMode) === 0) {
            return null;
        }

        $t4StaticParameters = self::calcStatisticParameters($t4Mode);
        $rndStaticParameters = self::calcStatisticParameters($rndMode);
        $rvdStaticParameters = self::calcStatisticParameters($rvdMode);

        $rT4Rnd = self::calcCorrelationCoefficient($t4Mode, $rndMode);
        $rT4Rvd = self::calcCorrelationCoefficient($t4Mode, $rvdMode);
        $rRndRvd = self::calcCorrelationCoefficient($rndMode, $rvdMode);

        $ddT4Rnd = self::calcDistributionDensity($timeMode, $t4Mode, $rndMode, $t4StaticParameters['average'], $rndStaticParameters['average'], $t4StaticParameters['sampleVariance'], $rndStaticParameters['sampleVariance'], $rT4Rnd);
        $ddT4Rvd = self::calcDistributionDensity($timeMode, $t4Mode, $rvdMode, $t4StaticParameters['average'], $rvdStaticParameters['average'], $t4StaticParameters['sampleVariance'], $rvdStaticParameters['sampleVariance'], $rT4Rvd);
        $ddRndRvd = self::calcDistributionDensity($timeMode, $rndMode, $rvdMode, $rndStaticParameters['average'], $rvdStaticParameters['average'], $rndStaticParameters['sampleVariance'], $rvdStaticParameters['sampleVariance'], $rRndRvd);

        return [
            't4' => $t4StaticParameters,
            'rnd' => $rndStaticParameters,
            'rvd' => $rvdStaticParameters,
            'rT4Rnd' => $rT4Rnd,
            'rT4Rvd' => $rT4Rvd,
            'rRndRvd' => $rRndRvd,
            'timeMode' => $timeMode,
            'ddT4Rnd' => $ddT4Rnd,
            'ddT4Rvd' => $ddT4Rvd,
            'ddRndRvd' => $ddRndRvd,
        ];
    }

    private static function calcStatisticParameters(array $parameters): array
    {
        $average = array_sum($parameters) / count($parameters);

        $sum = 0;
        foreach ($parameters as $parameter) {
            $sum += (($parameter - $average) ** 2);
        }
        $sampleVariance = $sum / (count($parameters));

        $rootMeanSquareDeviation = sqrt($sampleVariance);

        $coefficientOfVariation = $rootMeanSquareDeviation / $average * 100;

        $standardErrorOfTheMean = $rootMeanSquareDeviation / sqrt(count($parameters));

        $numberOfDegreesOfFreedom = count($parameters) - 1;

        return ['average' => $average, 'sampleVariance' => $sampleVariance, 'rootMeanSquareDeviation' => $rootMeanSquareDeviation, 'coefficientOfVariation' => $coefficientOfVariation, 'standardErrorOfTheMean' => $standardErrorOfTheMean, 'numberOfDegreesOfFreedom' => $numberOfDegreesOfFreedom];
    }

    private static function calcCorrelationCoefficient(array $parameter1, array $parameter2): float
    {
        $averageT4 = array_sum($parameter1) / count($parameter1);
        $averageRnd = array_sum($parameter2) / count($parameter2);

        $valueT4 = [];
        $sumT4 = 0;
        foreach ($parameter1 as $value) {
            $valueT4[] = $value - $averageT4;
            $sumT4 += (($value - $averageT4) ** 2);
        }
        $valueRnd = [];
        $sumRnd = 0;
        foreach ($parameter2 as $value) {
            $valueRnd[] = $value - $averageRnd;
            $sumRnd += (($value - $averageRnd) ** 2);
        }
        $sum = array_map(function ($t4, $rnd) {
            return $t4 * $rnd;
        }, $valueT4, $valueRnd);
        $sum = array_sum($sum);

        $denominator = $sumT4 * $sumRnd;
        return $sum / sqrt($denominator);
    }

    private static function calcDistributionDensity(
        array $time,
        array $parameter1,
        array $parameter2,
        float $averageParameter1,
        float $averageParameter2,
        float $sampleVarianceParameter1,
        float $sampleVarianceParameter2,
        float $correlationCoefficient
    ): array {
        $dd = [];
        foreach ($time as $t) {
            $param1 = $parameter1[$t];
            $param2 = $parameter2[$t];
            $multiplier1 = 1 / (2 * M_PI * $sampleVarianceParameter1 * $sampleVarianceParameter2 * sqrt(1 - $correlationCoefficient ** 2));
            $multiplier2 = exp( - (1 / 2 * (1 - $correlationCoefficient ** 2)) * (($param1 - $averageParameter1) ** 2 / $sampleVarianceParameter1 ** 2 - 2 * $correlationCoefficient * (($param1 - $averageParameter1) * ($param2 - $averageParameter2)) / ($sampleVarianceParameter1 * $sampleVarianceParameter2)) - (($param2 - $averageParameter2) ** 2) / $sampleVarianceParameter2 ** 2);
            $dd[$t] = $multiplier1 * $multiplier2;
        }
        return $dd;
    }

    /**
     * @param int[] $time
     * @param float[] $t4
     * @param float[] $alfaRud
     * @param float[] $rnd
     * @param float[] $rvd
     */
    private static function createEngineParameterCollection(array $time, array $t4, array $alfaRud, array $rnd, array $rvd, ?array $parameters): EngineParameterCollection
    {
        $engineParameters = [];
        foreach ($time as $value) {
            $engineParameters[] = new EngineParameter(
                $value,
                $t4[$value],
                $alfaRud[$value],
                $rnd[$value],
                $rvd[$value]
            );
        }

        $calcParameter = null;
        $mutualParameters = [];
        if (is_array($parameters)) {
            $t4 = new CalcEngineValue(
                $parameters['t4']['average'],
                $parameters['t4']['sampleVariance'],
                $parameters['t4']['rootMeanSquareDeviation'],
                $parameters['t4']['coefficientOfVariation'],
                $parameters['t4']['standardErrorOfTheMean'],
                $parameters['t4']['numberOfDegreesOfFreedom']
            );
            $rnd = new CalcEngineValue(
                $parameters['rnd']['average'],
                $parameters['rnd']['sampleVariance'],
                $parameters['rnd']['rootMeanSquareDeviation'],
                $parameters['rnd']['coefficientOfVariation'],
                $parameters['rnd']['standardErrorOfTheMean'],
                $parameters['rnd']['numberOfDegreesOfFreedom']
            );
            $rvd = new CalcEngineValue(
                $parameters['rvd']['average'],
                $parameters['rvd']['sampleVariance'],
                $parameters['rvd']['rootMeanSquareDeviation'],
                $parameters['rvd']['coefficientOfVariation'],
                $parameters['rvd']['standardErrorOfTheMean'],
                $parameters['rvd']['numberOfDegreesOfFreedom']
            );
            $calcParameter = new CalcEngineParameter(
                $t4,
                $rnd,
                $rvd,
                $parameters['rT4Rnd'],
                $parameters['rT4Rvd'],
                $parameters['rRndRvd']
            );

            foreach ($parameters['timeMode'] as $time) {
                $mutualParameters[] = new MutualParameter(
                    $time,
                    $parameters['ddT4Rnd'][$time],
                    $parameters['ddT4Rvd'][$time],
                    $parameters['ddRndRvd'][$time]
                );
            }
        }

        return new EngineParameterCollection($engineParameters, $calcParameter, $mutualParameters);
    }
}