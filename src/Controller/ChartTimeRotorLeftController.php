<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\AirplaneRepository;
use App\Repository\FlightInformationRepository;
use App\Services\MathService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ChartTimeRotorLeftController extends AbstractController
{
    private FlightInformationRepository $flightInformationRepository;

    private MathService $mathService;

    private AirplaneRepository $airplaneRepository;

    public function __construct(
        FlightInformationRepository $flightInformationRepository,
        MathService $mathService,
        AirplaneRepository $airplaneRepository
    )
    {
        $this->flightInformationRepository = $flightInformationRepository;
        $this->mathService = $mathService;
        $this->airplaneRepository = $airplaneRepository;
    }

    /**
     * @Route("/charttimeleftrotor/{airplaneId}", name="chart_time_left_rotor", methods={"GET"})
     * @param int $airplaneId
     * @return Response
     */
    public function chartTimeLeftRotor(int $airplaneId): Response
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
            $t = $this->mathService->approximationTimeRotor($timeRndLeftRotor);
            if (is_float($t)) {
                $approximationTimeRndLeft[$number] = $t;
            }
            $t = $this->mathService->approximationTimeRotor($timeRvdLeftRotor);
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