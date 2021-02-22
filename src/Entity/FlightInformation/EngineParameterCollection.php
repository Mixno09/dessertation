<?php

declare(strict_types=1);

namespace App\Entity\FlightInformation;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class EngineParameterCollection
{
    private int $id;
    private Collection $collection;

    /**
     * @param EngineParameter[] $engineParameters
     */
    public function __construct(array $engineParameters)
    {
        $this->setCollection($engineParameters);
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
        return new AverageEngineParameter(10, 58, 59);
    }
}