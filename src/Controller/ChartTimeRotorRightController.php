<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\AirplaneRepository;
use App\Repository\FlightInformationRepository;
use App\Services\MathService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ChartTimeRotorRightController extends AbstractController
{
    private FlightInformationRepository $flightInformationRepository;

    private AirplaneRepository $airplaneRepository;

    private MathService $mathService;

    /**
     * ChartTimeRotorRightController constructor.
     * @param FlightInformationRepository $flightInformationRepository
     * @param AirplaneRepository $airplaneRepository
     * @param MathService $mathService
     */
    public function __construct(FlightInformationRepository $flightInformationRepository, AirplaneRepository $airplaneRepository, MathService $mathService)
    {
        $this->flightInformationRepository = $flightInformationRepository;
        $this->airplaneRepository = $airplaneRepository;
        $this->mathService = $mathService;
    }

    /**
     * @Route("/charttimerightrotor/{airplaneId}", name="chart_time_right_rotor", methods={"GET"})
     * @param int $airplaneId
     * @return Response
     */
    public function chartTimeRightRotor(int $airplaneId): Response
    {
        $departures = $this->airplaneRepository->findAirplanesById($airplaneId);
        $flightInformationRepository = $this->flightInformationRepository;

        $timeRndRight = [];
        $timeRvdRight = [];
        $approximationTimeRndRight = [];
        $approximationTimeRvdRight = [];
        foreach ($departures as $departure) {
            $departureId = (int) $departure['id'];
            $number = $departure['departure'];
            $timeRndRightRotor = $flightInformationRepository->findTimeRndRightRotor($departureId);
            $timeRvdRightRotor = $flightInformationRepository->findTimeRvdRightRotor($departureId);
            if (count($timeRndRightRotor) > 0) {
                $timeRndRight[$number] = count($timeRndRightRotor);
            }
            if (count($timeRvdRightRotor) > 0) {
                $timeRvdRight[$number] = count($timeRvdRightRotor);
            }
            $t = $this->mathService->approximationTimeRotor($timeRndRightRotor);
            if (is_float($t)) {
                $approximationTimeRndRight[$number] = $t;
            }
            $t = $this->mathService->approximationTimeRotor($timeRvdRightRotor);
            if (is_float($t)) {
                $approximationTimeRvdRight[$number] = $t;
            }
        }

        return $this->render('chart/time_rotor_right.html.twig', [
            'count_rnd_right' => array_keys($timeRndRight),
            'time_rnd_right' => array_values($timeRndRight),
            'time_rvd_right' => array_values($timeRvdRight),
            'approximation_time_rnd_right' => array_values($approximationTimeRndRight),
            'approximation_time_rvd_right' => array_values($approximationTimeRvdRight),
        ]);
    }
}