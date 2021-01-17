<?php

/** @noinspection PhpUnusedPrivateFieldInspection */

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class FlightInformation
{
    private $primaryKey;
    private FlightInformationId $id;
    private Collection $points;
    private FlightInformationRunOutRotor $runOutRotor;
    private string $slug;

    public function __construct(FlightInformationId $id, array $points)
    {
        $this->setId($id);
        $this->setPoints(...$points);
        $this->runOutRotor = FlightInformationRunOutRotor::fromPoints($points);
        $this->setSlug($id);
    }

    private function setId(FlightInformationId $id): void
    {
        $this->id = $id;
    }

    private function setPoints(FlightInformationPoint ...$points): void
    {
        $this->points = new ArrayCollection($points);
    }

    private function setSlug(FlightInformationId $id): void
    {
        $this->slug = implode('_', [
            $id->getDate()->format('Y-m-d'),
            $id->getDeparture(),
        ]);
    }

    public function getId(): FlightInformationId
    {
        return $this->id;
    }

    /**
     * @return FlightInformationPoint[]
     */
    public function getPoints(): array
    {
        return $this->points->toArray();
    }

    public function getRunOutRotor(): FlightInformationRunOutRotor
    {
        return $this->runOutRotor;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }
}