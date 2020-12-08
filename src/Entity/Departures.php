<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Departures
 *
 * @ORM\Table(name="departures", uniqueConstraints={@ORM\UniqueConstraint(name="departures_airplane_date_departure_uindex", columns={"airplane", "date", "departure"})})
 * @ORM\Entity
 */
class Departures
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false, options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="airplane", type="integer", nullable=false, options={"unsigned"=true,"comment"="номер борта"})
     */
    private $airplane;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="date", type="date", nullable=true, options={"comment"="дата вылета"})
     */
    private $date;

    /**
     * @var int
     *
     * @ORM\Column(name="departure", type="integer", nullable=false, options={"unsigned"=true,"comment"="номер вылета"})
     */
    private $departure;


}
