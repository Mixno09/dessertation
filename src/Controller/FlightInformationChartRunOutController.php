<?php

namespace App\Controller;

use App\Repository\FlightInformationRepository;
use App\ViewModel\FlightInformationChartRunOut;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FlightInformationChartRunOutController extends AbstractController
{
    private FlightInformationRepository $repository;

    public function __construct(FlightInformationRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @Route("/flightinformation/run-out/{airplane}", name="flight_information_chart_run_out_left", methods={"GET"})
     */
    public function runOutLeft(string $airplane): Response
    {
        $runOutRotor = $this->repository->findByAirplane($airplane);
        var_dump($runOutRotor);die();
        $viewModel = new FlightInformationChartRunOut($runOutRotor);
        return $this->render('chart/runout_rotor.twig', [
            'viewModel' => $viewModel,
        ]);
    }

    /**
     * @Route("/flightinformation/run-out/{airplane}", name="flight_information_chart_run_out_right", methods={"GET"})
     */
    public function runOutRight(string $airplane)
    {
        $runOutRotor = $this->repository->findByAirplane($airplane);
        $viewModel = new FlightInformationChartRunOut($runOutRotor);

        return $this->render('chart/runout_rotor.twig', [
            'viewModel' => $viewModel,
        ]);
    }
}
