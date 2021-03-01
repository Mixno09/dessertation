<?php

declare(strict_types=1);

namespace App\Entity\FlightInformation;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class EngineParameterCollection
{
    private ?int $id;
    private Collection $collection;
    private AverageEngineParameter $averageParameter;

    /**
     * @param EngineParameter[] $engineParameters
     */
    public function __construct(array $engineParameters, AverageEngineParameter $averageParameter)
    {
        $this->setCollection($engineParameters);
        $this->averageParameter = $averageParameter;
    }

    /**
     * @param EngineParameter[] $engineParameters
     */
    private function setCollection(array $engineParameters): void
    {
        $this->collection = new ArrayCollection($engineParameters);
    }

    public function averageParameter(): AverageEngineParameter
    {
        return $this->averageParameter; //todo сделать реализацию
    }
}