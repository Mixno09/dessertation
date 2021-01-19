<?php

namespace App\Controller;

use App\Repository\FlightInformationRepository;
use App\Service\MathService;
use App\ViewModel\FlightInformationChart;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FlightInformationChartController extends AbstractController
{
    private FlightInformationRepository $repository;

    public function __construct(FlightInformationRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @Route("/flight-informations/{slug}/chart", name="flight_information_chart", methods="GET")
     */
    public function index(string $slug): Response
    {
        $flightInformation = $this->repository->findBySlug($slug);
        $viewModel = new FlightInformationChart($flightInformation);

        return $this->render('chart/index.html.twig', [
            'viewModel' => $viewModel,
        ]);
    }
}
