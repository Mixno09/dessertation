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

        $flightInformation = $this->repository->find($flightInformationId);
        if ($flightInformation instanceof FlightInformation) {
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
    private function calcAverageParameter(array $engineParameters): AverageEngineParameter
    {
        return new AverageEngineParameter(10, 8, 4); //todo сделать реализацию
    }
}