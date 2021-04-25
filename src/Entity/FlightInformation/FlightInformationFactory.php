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

        $leftAverageParameter = self::calcAverageParameter($leftEngineParameters);
        $rightAverageParameter = self::calcAverageParameter($rightEngineParameters);

        $slug = implode('_', [
            $flightInformationId->getAirplaneNumber(),
            $flightInformationId->getFlightDate()->format('Y-m-d'),
            $flightInformationId->getFlightNumber()
        ]);

        return new FlightInformation(
            $flightInformationId,
            new EngineParameterCollection($leftEngineParameters, $leftAverageParameter),
            new EngineParameterCollection($rightEngineParameters, $rightAverageParameter),
            $slug
        );
    }

    /**
     * @param EngineParameter[] $engineParameters
     */
    private static function calcAverageParameter(array $engineParameters): ?AverageEngineParameter
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

        $averageT4 = array_sum($t4) / count($t4);
        $averageRnd = array_sum($rnd) / count($rnd);
        $averageRvd = array_sum($rvd) / count($rvd);

        return new AverageEngineParameter($averageT4, $averageRnd, $averageRvd);
    }
}