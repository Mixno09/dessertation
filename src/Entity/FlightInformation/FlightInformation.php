<?php

/** @noinspection PhpUnusedPrivateFieldInspection */

declare(strict_types=1);

namespace App\Entity\FlightInformation;

class FlightInformation
{
    private int $id;
    private FlightInformationId $flightInformationId;
    private EngineParameterCollection $leftEngineParameters;
    private EngineParameterCollection $rightEngineParameters;
    private string $slug;

    public function __construct(FlightInformationId $flightInformationId, EngineParameterCollection $leftEngineParameters, EngineParameterCollection $rightEngineParameters)
    {
        $this->flightInformationId = $flightInformationId;
        $this->leftEngineParameters = $leftEngineParameters;
        $this->rightEngineParameters = $rightEngineParameters;
        $this->setSlug($flightInformationId);
    }

    private function setSlug(FlightInformationId $id): void
    {
        $this->slug = implode('_', [
            $id->getAirplaneNumber(),
            $id->getFlightDate()->format('Y-m-d'),
            $id->getFlightNumber()
        ]);
    }

    public function getFlightInformationId(): FlightInformationId
    {
        return $this->flightInformationId;
    }

    public function getLeftEngineParameters(): EngineParameterCollection
    {
        return $this->leftEngineParameters;
    }

    public function getRightEngineParameters(): EngineParameterCollection
    {
        return $this->rightEngineParameters;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }
}