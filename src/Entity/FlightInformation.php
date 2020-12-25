<?php

/** @noinspection PhpUnusedPrivateFieldInspection */

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class FlightInformation
{
    private int $primaryKey;
    private FlightInformationId $id;
    private Collection $points;

    public function __construct(FlightInformationId $id, array $points)
    {
        $this->setId($id);
        $this->setPoints(...$points);
    }

    public function getPoints(): array
    {
        return $this->points->toArray();
    }

    private function setPoints(FlightInformationPoint ...$points): void
    {
        $this->points = new ArrayCollection($points);
    }

    private function setId(FlightInformationId $id): void
    {
        $this->id = $id;
    }

    public function getId(): FlightInformationId
    {
        return $this->id;
    }
}