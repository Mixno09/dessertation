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
    private string $slug;

    public function __construct(FlightInformationId $id, array $points)
    {
        $this->setId($id);
        $this->setPoints(...$points);
        $this->setSlug($id);
    }

    /**
     * @return FlightInformationPoint[]
     */
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

    public function getSlug(): string
    {
        return $this->slug;
    }

    private function setSlug(FlightInformationId $id): void
    {
        $this->slug = implode('_', [
            $id->getAirplane(),
            $id->getDate()->format('Y-m-d'),
            $id->getDeparture()
        ]);
    }
}