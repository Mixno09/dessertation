<?php

declare(strict_types=1);

namespace App\Domain;

class ChangeRevs
{
    private array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function stopRevs(int $stopTime): int
    {
        $engineRevs = [];
        foreach ($this->data as $revs) {
            $time = $revs['time'];
            if (array_key_exists('rnd_left', $revs)) {
                $engineRevs[$time] = $revs['rnd_left'];
            } elseif (array_key_exists('rvd_left', $revs)) {
                $engineRevs[$time] = $revs['rvd_left'];
            } elseif (array_key_exists('rnd_right', $revs)) {
                $engineRevs[$time] = $revs['rnd_right'];
            } elseif (array_key_exists('rvd_right', $revs)) {
                $engineRevs[$time] = $revs['rvd_right'];
            }
        }

        $engineRevs = array_slice($engineRevs, $stopTime, null, true);
        $key = '';
        foreach ($engineRevs as $key => $value) {
            if ($engineRevs[$key] >= 11) {
                continue;
            }
            break;
        }
        return $key;
    }

    public function approximation(int $stopTime, int $stopRevs): float
    {

    }
}