<?php

declare(strict_types=1);

namespace App\ViewModel\Chart;

class FlightInformationChart
{
    public array $labels;
    public array $t4Right;
    public array $t4Left;
    public array $alfaRight;
    public array $alfaLeft;
    public array $rndRight;
    public array $rndLeft;
    public array $rvdRight;
    public array $rvdLeft;
    public array $t4Ticks;
    public array $alfaRudTicks;
    public array $rndTicks;
    public array $rvdTicks;

    public function __construct(
        array $labels,
        array $t4Right,
        array $t4Left,
        array $alfaRight,
        array $alfaLeft,
        array $rndRight,
        array $rndLeft,
        array $rvdRight,
        array $rvdLeft,
        array $t4Ticks,
        array $alfaRudTicks,
        array $rndTicks,
        array $rvdTicks
    ) {
        $this->labels = $labels;
        $this->t4Right = $t4Right;
        $this->t4Left = $t4Left;
        $this->alfaRight = $alfaRight;
        $this->alfaLeft = $alfaLeft;
        $this->rndRight = $rndRight;
        $this->rndLeft = $rndLeft;
        $this->rvdRight = $rvdRight;
        $this->rvdLeft = $rvdLeft;
        $this->t4Ticks = $t4Ticks;
        $this->alfaRudTicks = $alfaRudTicks;
        $this->rndTicks = $rndTicks;
        $this->rvdTicks = $rvdTicks;
    }
}