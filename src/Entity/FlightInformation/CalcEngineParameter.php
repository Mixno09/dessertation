<?php

declare(strict_types=1);

namespace App\Entity\FlightInformation;

class CalcEngineParameter
{
    private CalcEngineValue $t4;
    private CalcEngineValue $rnd;
    private CalcEngineValue $rvd;
    private float $correlationCoefficientForT4Rnd;
    private float $correlationCoefficientForT4Rvd;
    private float $correlationCoefficientForRndRvd;

    public function __construct(
        CalcEngineValue $t4,
        CalcEngineValue $rnd,
        CalcEngineValue $rvd,
        float $correlationCoefficientForT4Rnd,
        float $correlationCoefficientForT4Rvd,
        float $correlationCoefficientForRndRvd
    ) {
        $this->t4 = $t4;
        $this->rnd = $rnd;
        $this->rvd = $rvd;
        $this->correlationCoefficientForT4Rnd = $correlationCoefficientForT4Rnd;
        $this->correlationCoefficientForT4Rvd = $correlationCoefficientForT4Rvd;
        $this->correlationCoefficientForRndRvd = $correlationCoefficientForRndRvd;
    }

    public function getCorrelationCoefficientForT4Rnd(): float
    {
        return $this->correlationCoefficientForT4Rnd;
    }

    public function getCorrelationCoefficientForT4Rvd(): float
    {
        return $this->correlationCoefficientForT4Rvd;
    }

    public function getCorrelationCoefficientForRndRvd(): float
    {
        return $this->correlationCoefficientForRndRvd;
    }

    public function getT4(): float
    {
        return $this->t4->getAverage();
    }

    public function getRnd(): float
    {
        return $this->rnd->getAverage();
    }

    public function getRvd(): float
    {
        return $this->rvd->getAverage();
    }

    public function getT4SampleVariance(): float
    {
        return $this->t4->getSampleVariance();
    }

    public function getRnd4SampleVariance(): float
    {
        return $this->rnd->getSampleVariance();
    }

    public function getRvdSampleVariance(): float
    {
        return $this->rvd->getSampleVariance();
    }

    public function getT4RootMeanSquareDeviation(): float
    {
        return $this->t4->getSampleVariance();
    }

    public function getRndRootMeanSquareDeviation(): float
    {
        return $this->rnd->getSampleVariance();
    }

    public function getRvdRootMeanSquareDeviation(): float
    {
        return $this->rvd->getSampleVariance();
    }
}