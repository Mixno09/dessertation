<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\AirplaneRepository;
use App\Repository\FlightInformationRepository;
use App\Services\MathService;
use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ChartTimeRotorLeftController extends AbstractController
{
    private Connection $connection;

    private FlightInformationRepository $flightInformationRepository;

    private MathService $mathService;

    private AirplaneRepository $airplaneRepository;

    public function __construct(
        Connection $connection,
        FlightInformationRepository $flightInformationRepository,
        MathService $mathService,
        AirplaneRepository $airplaneRepository
    )
    {
        $this->connection = $connection;
        $this->flightInformationRepository = $flightInformationRepository;
        $this->mathService = $mathService;
        $this->airplaneRepository = $airplaneRepository;
    }

    /**
     * @Route("/charttimerotor/{airplaneId}", name="chart_time_rotor", methods={"GET"})
     * @param int $airplaneId
     * @return Response
     */
    public function chartTimeRotor(int $airplaneId): Response
    {
        $departures = $this->airplaneRepository->findAirplanesById($airplaneId);

        $flightInformationRepository = $this->flightInformationRepository;

        $timeRndLeft = [];
        $timeRvdLeft = [];
        $approximationTimeRndLeft = [];
        $approximationTimeRvdLeft = [];

        foreach ($departures as $departure) {
            $departureId = (int)$departure['id'];
            $number = $departure['departure'];
            $timeRndLeftRotor = $flightInformationRepository->findTimeRndLeftRotor($departureId);
            $timeRvdLeftRotor = $flightInformationRepository->findTimeRvdLeftRotor($departureId);
            if (count($timeRndLeftRotor) > 0) {
                $timeRndLeft[$number] = count($timeRndLeftRotor);
            }
            if (count($timeRvdLeftRotor) > 0) {
                $timeRvdLeft[$number] = count($timeRvdLeftRotor);
            }
            $t = $this->mathService->getApproximationTimeRotor($timeRndLeftRotor);
            if (is_float($t)) {
                $approximationTimeRndLeft[$number] = $t;
            }
            $t = $this->mathService->getApproximationTimeRotor($timeRvdLeftRotor);
            if (is_float($t)) {
                $approximationTimeRvdLeft[$number] = $t;
            }
        }

        return $this->render('chart/time_rotor_left.html.twig', [
            'count_rnd_left' => array_keys($timeRndLeft),
            'time_rnd_left' => array_values($timeRndLeft),
            'time_rvd_left' => array_values($timeRvdLeft),
            'approximation_time_rnd_left' => array_values($approximationTimeRndLeft),
            'approximation_time_rvd_left' => array_values($approximationTimeRvdLeft),
        ]);
    }
}