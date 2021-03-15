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
    private bool $averageParameterError;

    /**
     * @param EngineParameter[] $engineParameters
     */
    public function __construct(array $engineParameters, ?AverageEngineParameter $averageParameter)
    {
        $this->setCollection($engineParameters);
        $this->setAverageParameter($averageParameter);
    }

    /**
     * @param EngineParameter[] $engineParameters
     */
    private function setCollection(array $engineParameters): void
    {
        $this->collection = new ArrayCollection($engineParameters);
    }

    private function setAverageParameter(?AverageEngineParameter $averageParameter): void
    {
        if ($averageParameter instanceof AverageEngineParameter) {
            $this->averageParameter = $averageParameter;
            $this->averageParameterError = false;
        } else {
            $this->averageParameter = new AverageEngineParameter(0, 0, 0);
            $this->averageParameterError = true;
        }
    }

    /**
     * @return AverageEngineParameter|null Возвращает null в случае невозможности расчета
     */
    public function averageParameter(): ?AverageEngineParameter
    {
        return ($this->averageParameterError ? null : $this->averageParameter);
    }
}