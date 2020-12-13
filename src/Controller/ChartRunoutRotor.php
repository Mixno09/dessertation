<?php

declare(strict_types=1);

namespace App\Controller;

use App\Module\Dessertation\Domain\FlightInformation\ChangeAlfaRUD;
use App\Module\Dessertation\Domain\FlightInformation\ChangeRevs;
use App\Repository\AirplaneRepository;
use App\Repository\FlightInformationRepository;
use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ChartRunoutRotor extends AbstractController
{
    private Connection $connection;
    private AirplaneRepository $airplaneRepository;
    private FlightInformationRepository $flightInformationRepository;

    public function __construct(Connection $connection, AirplaneRepository $airplaneRepository, FlightInformationRepository $flightInformationRepository)
    {
        $this->connection = $connection;
        $this->airplaneRepository = $airplaneRepository;
        $this->flightInformationRepository = $flightInformationRepository;
    }

    /**
     * @Route("/runoutrotorleft/{id}", name="runout_rotor_left", methods={"GET"})
     * @param int $id
     * @return Response
     */
    public function runoutRotorLeft(int $id): Response
    {
        $airplane = $this->airplaneRepository->findAirplanesById($id);

        $labels = [];
        $runoutRotorRndLeft = [];
        $runoutRotorRvdLeft = [];
        $approximationRunoutRotorRndLeft = [];
        $approximationRunoutRotorRvdLeft = [];

        foreach ($airplane as $data) {
            $airplaneId = (int) $data['id'];
            $departure = (int) $data['departure'];
            $labels[] = $data['departure'];
            $alfaLeft = $this->flightInformationRepository->findAlfaRudLeft($airplaneId);
            $revsRndLeft = $this->flightInformationRepository->findRevsRndLeft($airplaneId);
            $revsRvdLeft = $this->flightInformationRepository->findRevsRvdLeft($airplaneId);
            $changeAlfaRUDLeft = new ChangeAlfaRUD($alfaLeft);
            $stopTimeLeft = $changeAlfaRUDLeft->stopTime();
            $changeRevsRndLeft = new ChangeRevs($revsRndLeft);
            $stopRevsRndLeft = $changeRevsRndLeft->stopRevs($stopTimeLeft);
            $changeRevsRvdLeft = new ChangeRevs($revsRvdLeft);
            $stopRevsRvdLeft = $changeRevsRvdLeft->stopRevs($stopTimeLeft);
            $runoutRotorRndLeft[$departure] = $stopRevsRndLeft - $stopTimeLeft;
            $runoutRotorRvdLeft[$departure] = $stopRevsRvdLeft - $stopTimeLeft;
            $approximationRunoutRotorRndLeft[$departure] = $changeRevsRndLeft->approximation($stopTimeLeft, $stopRevsRndLeft);
            $approximationRunoutRotorRvdLeft[$departure] = $changeRevsRvdLeft->approximation($stopTimeLeft, $stopRevsRvdLeft);
        }

        return $this->render('chart/runout_rotor.twig', [
            'labels' => $labels,
            'time_runout_rotor_rnd' => 'Время выбега ротора РНД левого двигателя',
            'time_runout_rotor_rvd' => 'Время выбега ротора РВД левого двигателя',
            'approximation_time_runout_rotor_rnd' => 'Аппроксимированное время выбега ротора РНД левого двигателя',
            'approximation_time_runout_rotor_rvd' => 'Аппроксимированное время выбега ротора РНД левого двигателя',
            'runout_rnd' => array_values($runoutRotorRndLeft),
            'runout_rvd' => array_values($runoutRotorRvdLeft),
            'approximation_runout_rnd' => array_values($approximationRunoutRotorRndLeft),
            'approximation_runout_rvd' => array_values($approximationRunoutRotorRvdLeft),
        ]);
    }

    /**
     * @Route("/runoutrotorright/{id}", name="runout_rotor_right", methods={"GET"})
     * @param int $id
     * @return Response
     */
    public function runoutRotorRight(int $id): Response
    {
        $airplane = $this->airplaneRepository->findAirplanesById($id);

        $labels = [];
        $runoutRotorRndRight = [];
        $runoutRotorRvdRight = [];
        $approximationRunoutRotorRndRight = [];
        $approximationRunoutRotorRvdRight = [];
        foreach ($airplane as $data) {
            $airplaneId = (int) $data['id'];
            $departure = (int) $data['departure'];
            $labels[] = $data['departure'];
            $alfaRight = $this->flightInformationRepository->findAlfaRudRight($airplaneId);
            $revsRndRight = $this->flightInformationRepository->findRevsRndRight($airplaneId);
            $revsRvdRight = $this->flightInformationRepository->findRevsRvdRight($airplaneId);
            $changeAlfaRUDRight = new ChangeAlfaRUD($alfaRight);
            $stopTimeRight = $changeAlfaRUDRight->stopTime();
            $changeRevsRndRight = new ChangeRevs($revsRndRight);
            $stopRevsRndRight = $changeRevsRndRight->stopRevs($stopTimeRight);
            $changeRevsRvdRight = new ChangeRevs($revsRvdRight);
            $stopRevsRvdRight = $changeRevsRvdRight->stopRevs($stopTimeRight);
            $runoutRotorRndRight[$departure] = $stopRevsRndRight - $stopTimeRight;
            $runoutRotorRvdRight[$departure] = $stopRevsRvdRight - $stopTimeRight;
            $approximationRunoutRotorRndRight[$departure] = $changeRevsRndRight->approximation($stopTimeRight, $stopRevsRndRight);
            $approximationRunoutRotorRvdRight[$departure] = $changeRevsRvdRight->approximation($stopTimeRight, $stopRevsRvdRight);
        }

        return $this->render('chart/runout_rotor.twig', [
            'labels' => $labels,
            'time_runout_rotor_rnd' => 'Время выбега ротора РНД правого двигателя',
            'time_runout_rotor_rvd' => 'Время выбега ротора РВД правого двигателя',
            'approximation_time_runout_rotor_rnd' => 'Аппроксимированное время выбега ротора РНД правого двигателя',
            'approximation_time_runout_rotor_rvd' => 'Аппроксимированное время выбега ротора РНД правого двигателя',
            'runout_rnd' => array_values($runoutRotorRndRight),
            'runout_rvd' => array_values($runoutRotorRvdRight),
            'approximation_runout_rnd' => array_values($approximationRunoutRotorRndRight),
            'approximation_runout_rvd' => array_values($approximationRunoutRotorRvdRight),
        ]);
    }
}