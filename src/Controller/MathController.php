<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\FlightInformationRepository;
use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


final class MathController extends AbstractController
{
    /**
     * @var \Doctrine\DBAL\Connection
     */
    private $connection;

    /**
     * @var FlightInformationRepository
     */
    private $flightInformationRepository;

    /**
     * MathController constructor.
     * @param \Doctrine\DBAL\Connection $connection
     * @param \App\Repository\FlightInformationRepository $flightInformationRepository
     */
    public function __construct(Connection $connection, FlightInformationRepository $flightInformationRepository)
    {
        $this->connection = $connection;
        $this->flightInformationRepository = $flightInformationRepository;
    }

    /**
     * @Route("/math/{id}", name="math", methods={"GET"})
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function math(int $id): Response
    {
        $repository = $this->flightInformationRepository;
        $rnd_left = $repository->findRndLeft($id);
        $rnd_right = $repository->findRndRight($id);
        $countEngineShutdowns = $repository->findCountEngineShutdowns();

        $rndLeft = [];
        $timeLeft = [];
        $rndRight = [];
        $timeRight = [];
        $engineShutdowns = [];

        foreach ($rnd_left as $left) {
            $rndLeft[] = $left['rnd_left'];
            $timeLeft[] = $left['time'];
        }
        foreach ($rnd_right as $right) {
            $rndRight[] = $right['rnd_right'];
            $timeRight[] = $right['time'];
        }

        foreach ($countEngineShutdowns as $shutdown) {
            $engineShutdowns[] = $shutdown['departure'];
        }

        return $this->render('mathcalc/mathcalc.html.twig', [
            'rnd_left' => $rndLeft,
            'time_left' => $timeLeft,
            'rnd_right' => $rndRight,
            'time_right' => $timeRight,
            'engine_shutdowns' => $engineShutdowns,

        ]);
    }
}