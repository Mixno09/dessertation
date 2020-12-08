<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RunoutRotor
 *
 * @ORM\Table(name="runout_rotor", indexes={@ORM\Index(name="runout_rotor_departure_index", columns={"departure"})})
 * @ORM\Entity
 */
class RunoutRotor
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="departure", type="integer", nullable=false, options={"unsigned"=true})
     */
    private $departure;

    /**
     * @var int
     *
     * @ORM\Column(name="airplane", type="integer", nullable=false)
     */
    private $airplane;

    /**
     * @var int
     *
     * @ORM\Column(name="runout_rnd_left", type="integer", nullable=false)
     */
    private $runoutRndLeft;

    /**
     * @var int
     *
     * @ORM\Column(name="runout_rvd_left", type="integer", nullable=false)
     */
    private $runoutRvdLeft;

    /**
     * @var int
     *
     * @ORM\Column(name="runout_rnd_right", type="integer", nullable=false)
     */
    private $runoutRndRight;

    /**
     * @var int
     *
     * @ORM\Column(name="runout_rvd_right", type="integer", nullable=false)
     */
    private $runoutRvdRight;

    /**
     * @var float
     *
     * @ORM\Column(name="approximation_rnd_left", type="float", precision=10, scale=0, nullable=false)
     */
    private $approximationRndLeft;

    /**
     * @var float
     *
     * @ORM\Column(name="approximation_rvd_left", type="float", precision=10, scale=0, nullable=false)
     */
    private $approximationRvdLeft;

    /**
     * @var float
     *
     * @ORM\Column(name="approximation_rnd_right", type="float", precision=10, scale=0, nullable=false)
     */
    private $approximationRndRight;

    /**
     * @var float
     *
     * @ORM\Column(name="approximation_rvd_right", type="float", precision=10, scale=0, nullable=false)
     */
    private $approximationRvdRight;


}
