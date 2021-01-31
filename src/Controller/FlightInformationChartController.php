<?php

namespace App\Controller;

use App\UseCase\Query\FlightInformationChartHandler;
use App\UseCase\Query\FlightInformationChartQuery;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FlightInformationChartController extends AbstractController
{
    private FlightInformationChartHandler $handle;

    public function __construct(FlightInformationChartHandler $handle)
    {
        $this->handle = $handle;
    }

    /**
     * @Route("/flight-informations/{slug}/chart", name="flight_information_chart", methods="GET")
     */
    public function index(string $slug): Response
    {
        $query = new FlightInformationChartQuery();
        $query->slug = $slug;
        $flightInformationChart = $this->handle->handle($query);
        
        return $this->render('chart/index.html.twig', [
            'flightInformationChart' => $flightInformationChart,
        ]);
    }
}
