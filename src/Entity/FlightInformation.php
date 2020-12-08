<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FlightInformation
 *
 * @ORM\Table(name="flight_information", indexes={@ORM\Index(name="flight_information_departures_id_fk", columns={"departure_id"})})
 * @ORM\Entity
 */
class FlightInformation
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false, options={"unsigned"=true,"comment"="первичный ключ"})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="time", type="integer", nullable=false, options={"unsigned"=true})
     */
    private $time;

    /**
     * @var int
     *
     * @ORM\Column(name="t4_right", type="integer", nullable=false)
     */
    private $t4Right;

    /**
     * @var int
     *
     * @ORM\Column(name="t4_left", type="integer", nullable=false)
     */
    private $t4Left;

    /**
     * @var string
     *
     * @ORM\Column(name="alfa_rud_left", type="decimal", precision=4, scale=1, nullable=false)
     */
    private $alfaRudLeft;

    /**
     * @var string
     *
     * @ORM\Column(name="alfa_rud_right", type="decimal", precision=4, scale=1, nullable=false)
     */
    private $alfaRudRight;

    /**
     * @var string
     *
     * @ORM\Column(name="rnd_left", type="decimal", precision=4, scale=1, nullable=false)
     */
    private $rndLeft;

    /**
     * @var string
     *
     * @ORM\Column(name="rvd_left", type="decimal", precision=4, scale=1, nullable=false)
     */
    private $rvdLeft;

    /**
     * @var string
     *
     * @ORM\Column(name="rnd_right", type="decimal", precision=4, scale=1, nullable=false)
     */
    private $rndRight;

    /**
     * @var string
     *
     * @ORM\Column(name="rvd_right", type="decimal", precision=4, scale=1, nullable=false)
     */
    private $rvdRight;

    /**
     * @var \Departures
     *
     * @ORM\ManyToOne(targetEntity="Departures")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="departure_id", referencedColumnName="id")
     * })
     */
    private $departure;


}
