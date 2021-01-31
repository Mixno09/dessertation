<?php

namespace App\Controller;

use App\UseCase\Query\RunOutChartQuery;
use App\UseCase\Query\RunOutHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FlightInformationChartRunOutController extends AbstractController
{
    private RunOutHandler $handler;

    public function __construct(RunOutHandler $handler)
    {
        $this->handler = $handler;
    }

    /**
     * @Route("/flightinformation/run-out/{airplane}/left", name="flight_information_chart_run_out_left", methods={"GET"})
     */
    public function runOutLeft(string $airplane): Response
    {
        $query = new RunOutChartQuery();
        $query->airplane = $airplane;
        $runOut = $this->handler->handle($query);
        $runOutRotor = $runOut['left'];
        
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
        $query = new RunOutChartQuery();
        $query->airplane = $airplane;
        $runOut = $this->handler->handle($query);
        $runOutRotor = $runOut['right'];

        return $this->render('chart/runout_rotor.twig', [
            'runOutRotor' => $runOutRotor,
            'time_runout_rotor_rnd' => 'Время выбега ротора РНД правого двигателя',
            'time_runout_rotor_rvd' => 'Время выбега ротора РВД правого двигателя',
            'approximation_time_runout_rotor_rnd' => 'Аппроксимированное время выбега ротора РНД правого двигателя',
            'approximation_time_runout_rotor_rvd' => 'Аппроксимированное время выбега ротора РНД правого двигателя',
        ]);
    }
}
