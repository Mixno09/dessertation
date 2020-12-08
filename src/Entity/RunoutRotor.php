<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RunoutRotorRepository")
 */
class RunoutRotor
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $departure;

    /**
     * @ORM\Column(type="integer")
     */
    private $airplane;

    /**
     * @ORM\Column(type="integer")
     */
    private $runoutRndLeft;

    /**
     * @ORM\Column(type="integer")
     */
    private $runoutRvdLeft;

    /**
     * @ORM\Column(type="integer")
     */
    private $runoutRndRight;

    /**
     * @ORM\Column(type="integer")
     */
    private $runoutRvdRight;

    /**
     * @ORM\Column(type="float")
     */
    private $approximationRunoutRndLeft;

    /**
     * @ORM\Column(type="float")
     */
    private $approximationRvdLeft;

    /**
     * @ORM\Column(type="float")
     */
    private $approximationRndRight;

    /**
     * @ORM\Column(type="float")
     */
    private $approximationRvdRight;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDeparture(): ?int
    {
        return $this->departure;
    }

    public function setDeparture(int $departure): self
    {
        $this->departure = $departure;

        return $this;
    }

    public function getAirplane(): ?int
    {
        return $this->airplane;
    }

    public function setAirplane(int $airplane): self
    {
        $this->airplane = $airplane;

        return $this;
    }

    public function getRunoutRndLeft(): ?int
    {
        return $this->runoutRndLeft;
    }

    public function setRunoutRndLeft(int $runoutRndLeft): self
    {
        $this->runoutRndLeft = $runoutRndLeft;

        return $this;
    }

    public function getRunoutRvdLeft(): ?int
    {
        return $this->runoutRvdLeft;
    }

    public function setRunoutRvdLeft(int $runoutRvdLeft): self
    {
        $this->runoutRvdLeft = $runoutRvdLeft;

        return $this;
    }

    public function getRunoutRndRight(): ?int
    {
        return $this->runoutRndRight;
    }

    public function setRunoutRndRight(int $runoutRndRight): self
    {
        $this->runoutRndRight = $runoutRndRight;

        return $this;
    }

    public function getRunoutRvdRight(): ?int
    {
        return $this->runoutRvdRight;
    }

    public function setRunoutRvdRight(int $runoutRvdRight): self
    {
        $this->runoutRvdRight = $runoutRvdRight;

        return $this;
    }

    public function getApproximationRunoutRndLeft(): ?float
    {
        return $this->approximationRunoutRndLeft;
    }

    public function setApproximationRunoutRndLeft(float $approximationRunoutRndLeft): self
    {
        $this->approximationRunoutRndLeft = $approximationRunoutRndLeft;

        return $this;
    }

    public function getApproximationRvdLeft(): ?float
    {
        return $this->approximationRvdLeft;
    }

    public function setApproximationRvdLeft(float $approximationRvdLeft): self
    {
        $this->approximationRvdLeft = $approximationRvdLeft;

        return $this;
    }

    public function getApproximationRndRight(): ?float
    {
        return $this->approximationRndRight;
    }

    public function setApproximationRndRight(float $approximationRndRight): self
    {
        $this->approximationRndRight = $approximationRndRight;

        return $this;
    }

    public function getApproximationRvdRight(): ?float
    {
        return $this->approximationRvdRight;
    }

    public function setApproximationRvdRight(float $approximationRvdRight): self
    {
        $this->approximationRvdRight = $approximationRvdRight;

        return $this;
    }
}
