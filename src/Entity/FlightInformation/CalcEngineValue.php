<?php

declare(strict_types=1);

namespace App\Entity\FlightInformation;

class CalcEngineValue
{
    private float $average;
    private float $sampleVariance;
    private float $rootMeanSquareDeviation;
    private float $coefficientOfVariation;
    private float $standardErrorOfTheMean;
    private float $numberOfDegreesOfFreedom;

    public function __construct(
        float $average,
        float $sampleVariance,
        float $rootMeanSquareDeviation,
        float $coefficientOfVariation,
        float $standardErrorOfTheMean,
        float $numberOfDegreesOfFreedom
    ) {
        $this->average = $average;
        $this->sampleVariance = $sampleVariance;
        $this->rootMeanSquareDeviation = $rootMeanSquareDeviation;
        $this->coefficientOfVariation = $coefficientOfVariation;
        $this->standardErrorOfTheMean = $standardErrorOfTheMean;
        $this->numberOfDegreesOfFreedom = $numberOfDegreesOfFreedom;
    }

    /**
     * Среднее арифметическое
     */
    public function getAverage(): float
    {
        return $this->average;
    }

    public function getSampleVariance(): float
    {
        return $this->sampleVariance;
    }

    public function getRootMeanSquareDeviation(): float
    {
        return $this->rootMeanSquareDeviation;
    }

    public function getCoefficientOfVariation(): float
    {
        return $this->coefficientOfVariation;
    }

    public function getStandardErrorOfTheMean(): float
    {
        return $this->standardErrorOfTheMean;
    }

    public function getNumberOfDegreesOfFreedom(): float
    {
        return $this->numberOfDegreesOfFreedom;
    }
}