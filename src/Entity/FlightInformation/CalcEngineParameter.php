<?php

declare(strict_types=1);

namespace App\Entity\FlightInformation;

class CalcEngineParameter
{
    private CalcEngineValue $t4;
    private CalcEngineValue $rnd;
    private CalcEngineValue $rvd;

    public function __construct(CalcEngineValue $t4, CalcEngineValue $rnd, CalcEngineValue $rvd)
    {
        $this->t4 = $t4;
        $this->rnd = $rnd;
        $this->rvd = $rvd;
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
}