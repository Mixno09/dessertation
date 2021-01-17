<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Exception;

class Airplane
{
    private $primaryKey;
    private AirplaneId $id; //todo embedded
    private Collection $flightInformation; //todo one-to-many
    private string $slug;

    public function __construct(AirplaneId $id)
    {
        $this->id = $id;
        $this->flightInformation = new ArrayCollection();
        $this->setSlug();
    }

    public function addFlightInformation(FlightInformation $flightInformation): void
    {
        $exist = $this->flightInformation->exists(
            fn($key, FlightInformation $element) => $element->getId()->equals($flightInformation->getId())
        );
        if ($exist) {
            throw new Exception('Такие данные уже существуют'); //todo написать нормальное сообщение
        }

        $this->flightInformation->add($flightInformation);
    }

    public function getFlightInformation(): array
    {
        return $this->flightInformation->toArray();
    }

    /**
     * @return string
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     */
    public function setSlug(): string
    {
        return implode('_', [
            $this->id->getAirplane(),
            $this->getFlightInformation()[''] // todo как получить дату и номер вылета из коллекции
        ]);
    }
}