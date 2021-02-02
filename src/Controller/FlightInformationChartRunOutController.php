<?php

namespace App\Controller;

use App\UseCase\Query\RunOutLeftHandler;
use App\UseCase\Query\RunOutLeftQuery;
use App\UseCase\Query\RunOutRightQuery;
use App\UseCase\Query\RunOutRightHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FlightInformationChartRunOutController extends AbstractController
{
    private RunOutLeftHandler $handlerLeft;
    private RunOutRightHandler $handlerRight;

    public function __construct(RunOutLeftHandler $handlerLeft, RunOutRightHandler $handlerRight)
    {
        $this->handlerLeft = $handlerLeft;
        $this->handlerRight = $handlerRight;
    }

    /**
     * @Route("/flightinformation/run-out/{airplane}/left", name="flight_information_chart_run_out_left", methods={"GET"})
     */
    public function runOutLeft(string $airplane): Response
    {
        $query = new RunOutLeftQuery();
        $query->airplane = $airplane;
        $runOutRotor = $this->handlerLeft->handle($query);

        return $this->render('chart/runout_rotor.twig', [
            'runOutRotor' => $runOutRotor,
            'time_runout_rotor_rnd' => 'Время выбега ротора РНД левого двигателя',
            'time_runout_rotor_rvd' => 'Время выбега ротора РВД левого двигателя',
            'approximation_time_runout_rotor_rnd' => 'Аппроксимированное время выбега ротора РНД левого двигателя',
            'approximation_time_runout_rotor_rvd' => 'Аппроксимированное время выбега ротора РНД левого двигателя',
        ]);
    }

    /**
     * @Route("/flightinformation/run-out/{airplane}/right", name="flight_information_chart_run_out_right", methods={"GET"})
     */
    public function runOutRight(string $airplane)
    {
        $query = new RunOutRightQuery();
        $query->airplane = $airplane;
        $runOutRotor = $this->handlerRight->handle($query);

        return $this->render('chart/runout_rotor.twig', [
            'runOutRotor' => $runOutRotor,
            'time_runout_rotor_rnd' => 'Время выбега ротора РНД правого двигателя',
            'time_runout_rotor_rvd' => 'Время выбега ротора РВД правого двигателя',
            'approximation_time_runout_rotor_rnd' => 'Аппроксимированное время выбега ротора РНД правого двигателя',
            'approximation_time_runout_rotor_rvd' => 'Аппроксимированное время выбега ротора РНД правого двигателя',
        ]);
    }
}
