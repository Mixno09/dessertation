<?php

declare(strict_types=1);

namespace App\ViewModel\Chart;

class RunOutRotor
{
    /**
     * @var int[]
     */
    private array $labels;
    /**
     * @var float[]
     */
    private array $rndRaw;
    /**
     * @var float[]
     */
    private array $rvdRaw;
    /**
     * @var float[]
     */
    private array $rndCalc;
    /**
     * @var float[]
     */
    private array $rvdCalc;

    /**
     * @param int[] $labels
     * @param float[] $rndRaw
     * @param float[] $rvdRaw
     * @param float[] $rndCalc
     * @param float[] $rvdCalc
     */
    public function __construct(array $labels, array $rndRaw, array $rvdRaw, array $rndCalc, array $rvdCalc)
    {
        $this->labels = $labels;
        $this->rndRaw = $rndRaw;
        $this->rvdRaw = $rvdRaw;
        $this->rndCalc = $rndCalc;
        $this->rvdCalc = $rvdCalc;
    }

    /**
     * @return int[]
     */
    public function getLabels(): array
    {
        return $this->labels;
    }

    /**
     * @return float[]
     */
    public function getRndRaw(): array
    {
        return $this->rndRaw;
    }

    /**
     * @return float[]
     */
    public function getRvdRaw(): array
    {
        return $this->rvdRaw;
    }

    /**
     * @return float[]
     */
    public function getRndCalc(): array
    {
        return $this->rndCalc;
    }

    /**
     * @return float[]
     */
    public function getRvdCalc(): array
    {
        return $this->rvdCalc;
    }
}