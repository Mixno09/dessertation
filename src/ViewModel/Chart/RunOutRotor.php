<?php

declare(strict_types=1);

namespace App\ViewModel\Chart;

class RunOutRotor
{
    public array $labels;
    public array $rndRaw;
    public array $rvdRaw;
    public array $rndCalc;
    public array $rvdCalc;
    public array $error;

    public function __construct(
        array $labels,
        array $rndRaw,
        array $rvdRaw,
        array $rndCalc,
        array $rvdCalc,
        array $error
    ) {
        $this->labels = $labels;
        $this->rndRaw = $rndRaw;
        $this->rvdRaw = $rvdRaw;
        $this->rndCalc = $rndCalc;
        $this->rvdCalc = $rvdCalc;
        $this->error = $error;
    }
}