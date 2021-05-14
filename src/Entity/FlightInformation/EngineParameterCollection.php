<?php

declare(strict_types=1);

namespace App\Entity\FlightInformation;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class EngineParameterCollection
{
    private ?int $id;
    private Collection $collection;
    private CalcEngineParameter $calcParameter;
    private bool $calcParameterError;

    /**
     * @param EngineParameter[] $engineParameters
     */
    public function __construct(array $engineParameters, ?CalcEngineParameter $calcParameter)
    {
        $this->setCollection($engineParameters);
        $this->setCalcParameter($calcParameter);
    }

    /**
     * @param EngineParameter[] $engineParameters
     */
    private function setCollection(array $engineParameters): void
    {
        $this->collection = new ArrayCollection($engineParameters);
    }

    private function setCalcParameter(?CalcEngineParameter $calcParameter): void
    {
        if ($calcParameter instanceof CalcEngineParameter) {
            $this->calcParameter = $calcParameter;
            $this->calcParameterError = false;
        } else {
            $value = new CalcEngineValue(0, 0, 0, 0, 0, 0);
            $this->calcParameter = new CalcEngineParameter($value, $value, $value);
            $this->calcParameterError = true;
        }
    }

    /**
     * @return CalcEngineParameter|null Возвращает null в случае невозможности расчета
     */
    public function calcParameter(): ?CalcEngineParameter
    {
        return ($this->calcParameterError ? null : $this->calcParameter);
    }
}