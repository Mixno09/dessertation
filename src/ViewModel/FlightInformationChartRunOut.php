<?php

declare(strict_types=1);

namespace App\ViewModel;

class FlightInformationChartRunOut
{
    private array $runOutRotor;

    public function __construct(array $runOutRotor)
    {
        $this->runOutRotor = $runOutRotor;
    }

    public function labels(): array
    {
        return $this->runOutRotor['departure'];
    }

    public function runOutRotorRndLeftRaw(): array
    {
        return $this->runOutRotor['run_out_rotor_rnd_left_raw'];
    }

    public function runOutRotorRndRightRaw(): array
    {
        return $this->runOutRotor['run_out_rotor_rnd_right_raw'];
    }

    public function runOutRotorRvdLeftRaw(): array
    {
        return $this->runOutRotor['run_out_rotor_rvd_left_raw'];
    }

    public function runOutRotorRvdRightRaw(): array
    {
        return $this->runOutRotor['run_out_rotor_rvd_right_raw'];
    }

    public function runOutRotorRndLeftCalc(): array
    {
        return $this->runOutRotor['run_out_rotor_rnd_left_calc'];
    }

    public function runOutRotorRndRightCalc(): array
    {
        return $this->runOutRotor['run_out_rotor_rnd_right_calc'];
    }

    public function runOutRotorRvdLeftCalc(): array
    {
        return $this->runOutRotor['run_out_rotor_rvd_left_calc'];
    }

    public function runOutRotorRvdRightCalc(): array
    {
        return $this->runOutRotor['run_out_rotor_rvd_right_calc'];
    }
}