<?php

declare(strict_types=1);

namespace App\Module\Dessertation\Domain\FlightInformation;

class FlightInformation
{
    private $primaryKey;
    private FlightInformationId $id;
    private FlightInformationData $data;

    public function __construct(FlightInformationId $id, FlightInformationData $data)
    {
        $this->setId($id);
        $this->setData($data);
    }

    public function getData(): FlightInformationData
    {
        return $this->data;
    }

    private function setData(FlightInformationData $data): void
    {
        $this->data = $data;
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