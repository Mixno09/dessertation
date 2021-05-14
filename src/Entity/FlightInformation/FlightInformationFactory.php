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

        $leftEngineParameters = [];
        $rightEngineParameters = [];
        foreach ($time as $value) {
            $leftEngineParameters[] = new EngineParameter(
                $value,
                $t4Left[$value],
                $alfaRudLeft[$value],
                $rndLeft[$value],
                $rvdLeft[$value]
            );
            $rightEngineParameters[] = new EngineParameter(
                $value,
                $t4Right[$value],
                $alfaRudRight[$value],
                $rndRight[$value],
                $rvdRight[$value]
            );
        }

        $leftCalcParameter = self::createCalcEngineParameter($leftEngineParameters);
        $rightCalcParameter = self::createCalcEngineParameter($rightEngineParameters);

        $slug = implode('_', [
            $flightInformationId->getAirplaneNumber(),
            $flightInformationId->getFlightDate()->format('Y-m-d'),
            $flightInformationId->getFlightNumber()
        ]);

        return new FlightInformation(
            $flightInformationId,
            new EngineParameterCollection($leftEngineParameters, $leftCalcParameter),
            new EngineParameterCollection($rightEngineParameters, $rightCalcParameter),
            $slug
        );
    }

    /**
     * @param EngineParameter[] $engineParameters
     */
    private static function createCalcEngineParameter(array $engineParameters): ?CalcEngineParameter
    {
        $t4 = [];
        $rnd = [];
        $rvd = [];
        foreach ($engineParameters as $parameter) {
            $parameterRnd = $parameter->getRnd();
            if ($parameterRnd > 31 && $parameterRnd < 36.5) {
                $time = $parameter->getTime();
                $t4[$time] = $parameter->getT4();
                $rnd[$time] = $parameterRnd;
                $rvd[$time] = $parameter->getRvd();
            }
        }

        if (count($t4) === 0 || count($rnd) === 0 || count($rvd) === 0) {
            return null;
        }

        $t4Value = self::createCalcEngineValue($t4);
        $rndValue = self::createCalcEngineValue($rnd);
        $rvdValue = self::createCalcEngineValue($rvd);

        return new CalcEngineParameter($t4Value, $rndValue, $rvdValue);
    }

    private static function createCalcEngineValue(array $parameters): CalcEngineValue
    {
        $average = array_sum($parameters) / count($parameters);

        $sum = 0;
        foreach ($parameters as $parameter) {
            $sum += (($parameter - $average) ** 2);
        }
        $sampleVariance = $sum / (count($parameters) - 1);

        $rootMeanSquareDeviation = sqrt($sampleVariance);

        $coefficientOfVariation = $rootMeanSquareDeviation / $average * 100;

        $standardErrorOfTheMean = $rootMeanSquareDeviation / sqrt(count($parameters));

        $numberOfDegreesOfFreedom = count($parameters) - 1;

        return new CalcEngineValue($average, $sampleVariance, $rootMeanSquareDeviation, $coefficientOfVariation, $standardErrorOfTheMean, $numberOfDegreesOfFreedom);
    }
}