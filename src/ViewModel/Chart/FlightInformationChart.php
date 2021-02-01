<?php

declare(strict_types=1);

namespace App\ViewModel\Chart;

class FlightInformationChart //todo сделать инициализацию через конструктор
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

    public function ticks(): void
    {
        $this->t4Ticks = $this->t4Ticks();
        $this->alfaRudTicks = $this->alfaRudTicks();
        $this->rndTicks = $this->rndTicks();
        $this->rvdTicks = $this->rvdTicks();
    }

    private function t4Ticks(): array
    {
        $min = 0;
        $max = 0;
        foreach ($this->t4Right as $t4Right) {
            $min = min($t4Right, $min);
            $max = max($t4Right, $max);
        }
        foreach ($this->t4Left as $t4Left) {
            $min = min($t4Left, $min);
            $max = max($t4Left, $max);
        }
        $min = (int)(floor($min / 100) * 100);
        $max = (int)(ceil($max / 100) * 100);
        $scale = $max - $min;
        $min -= $scale * 3;
        return ['min' => $min, 'max' => $max];
    }

    private function alfaRudTicks(): array
    {
        $min = 0;
        $max = 0;
        foreach ($this->alfaRight as $alfaRight) {
            $min = min($alfaRight, $min);
            $max = max($alfaRight, $max);
        }
        foreach ($this->alfaLeft as $alfaLeft) {
            $min = min($alfaLeft, $min);
            $max = max($alfaLeft, $max);
        }
        $min = (int)(floor($min / 10) * 10);
        $max = (int)(ceil($max / 10) * 10);
        $scale = $max - $min;
        $max += $scale;
        $min -= $scale * 2;
        return ['min' => $min, 'max' => $max];
    }

    private function rndTicks(): array
    {
        $min = 0;
        $max = 0;
        foreach ($this->rndRight as $rndRight) {
            $min = min($rndRight, $min);
            $max = max($rndRight, $max);
        }
        foreach ($this->rndLeft as $rndLeft) {
            $min = min($rndLeft, $min);
            $max = max($rndLeft, $max);
        }
        $min = (int)(floor($min / 25) * 25);
        $max = (int)(ceil($max / 25) * 25);
        $scale = $max - $min;
        $max += $scale * 2;
        $min -= $scale;
        return ['min' => $min, 'max' => $max];
    }

    private function rvdTicks(): array
    {
        $min = 0;
        $max = 0;
        foreach ($this->rvdRight as $rvdRight) {
            $min = min($rvdRight, $min);
            $max = max($rvdRight, $max);
        }
        foreach ($this->rvdLeft as $rvdLeft) {
            $min = min($rvdLeft, $min);
            $max = max($rvdLeft, $max);
        }
        $min = (int)(floor($min / 25) * 25);
        $max = (int)(ceil($max / 25) * 25);
        $scale = $max - $min;
        $max += $scale * 3;
        return ['min' => $min, 'max' => $max];
    }
}