<?php

declare(strict_types=1);

namespace App\UseCase\Command;

use App\Entity\FlightInformation\AverageEngineParameter;
use App\Entity\FlightInformation\EngineParameter;
use App\Entity\FlightInformation\EngineParameterCollection;
use App\Entity\FlightInformation\FlightInformation;
use App\Entity\FlightInformation\FlightInformationId;
use App\Repository\FlightInformationRepository;
use Exception;

class CreateFlightInformationHandler
{
    private FlightInformationRepository $repository;

    public function __construct(FlightInformationRepository $repository)
    {
        $this->repository = $repository;
    }

    public function handle(CreateFlightInformationCommand $command): void
    {
        $flightInformationId = new FlightInformationId(
            $command->getAirplaneNumber(),
            $command->getFlightDate(),
            $command->getFlightNumber()
        );

        $hasFlightInformation = $this->repository->hasFlightInformation($flightInformationId);
        if ($hasFlightInformation) {
            throw new Exception('Такие данные уже существуют в хранилище');
        }

        $leftEngineParameters = [];
        $rightEngineParameters = [];
        foreach ($command->getTime() as $time) {
            $leftEngineParameters[] = new EngineParameter(
                $time,
                $command->getT4Left()[$time],
                $command->getAlfaRudLeft()[$time],
                $command->getRndLeft()[$time],
                $command->getRvdLeft()[$time]
            );
            $rightEngineParameters[] = new EngineParameter(
                $time,
                $command->getT4Right()[$time],
                $command->getAlfaRudRight()[$time],
                $command->getRndRight()[$time],
                $command->getRvdRight()[$time]
            );
        }

        $leftAverageParameter = $this->calcAverageParameter($leftEngineParameters);
        $rightAverageParameter = $this->calcAverageParameter($rightEngineParameters);

        $flightInformation = new FlightInformation(
            $flightInformationId,
            new EngineParameterCollection($leftEngineParameters, $leftAverageParameter),
            new EngineParameterCollection($rightEngineParameters, $rightAverageParameter)
        );

        $this->repository->save($flightInformation);
    }

    /**
     * @param EngineParameter[] $engineParameters
     */
    private function calcAverageParameter(array $engineParameters): ?AverageEngineParameter
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