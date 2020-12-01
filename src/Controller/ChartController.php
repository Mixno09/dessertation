<?php

namespace App\Controller;

use App\Repository\FlightInformationRepository;
use App\Services\MathService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ChartController extends AbstractController
{
    private FlightInformationRepository $flightInformationRepository;

    private MathService $mathService;

    public function __construct(FlightInformationRepository $flightInformationRepository, MathService $mathService)
    {
        $this->flightInformationRepository = $flightInformationRepository;
        $this->mathService = $mathService;
    }

    /**
     * @Route("/departure/{id}", name="departure", methods="GET")
     * @param int $id
     * @return Response
     */
    public function index(int $id): Response
    {
        $params = $this->flightInformationRepository->findInformationById($id);

        $labels = [];
        $t4Right = [];
        $t4Left = [];
        $alfaRudLeft = [];
        $alfaRudRight = [];
        $rndLeft = [];
        $rndRight = [];
        $rvdLeft = [];
        $rvdRight = [];
        $departure_id = '';
        foreach ($params as $param) {
            $labels[] = $param['time'];
            $t4Right[] = $param['t4_right'];
            $t4Left[] = $param['t4_left'];
            $alfaRudLeft[] = $param['alfa_rud_left'];
            $alfaRudRight[] = $param['alfa_rud_right'];
            $rndLeft[] = $param['rnd_left'];
            $rndRight[] = $param['rnd_right'];
            $rvdLeft[] = $param['rvd_left'];
            $rvdRight[] = $param['rvd_right'];
            $departure_id = $param['departure_id'];
        }

        $mathService = $this->mathService;
        
        $t4Right = $mathService::filter($t4Right);
        $t4Left = $mathService::filter($t4Left);
        $alfaRudLeft = $mathService::filter($alfaRudLeft);
        $alfaRudRight = $mathService::filter($alfaRudRight);
        $rndLeft = $mathService::filter($rndLeft);
        $rndRight = $mathService::filter($rndRight);
        $rvdLeft = $mathService::filter($rvdLeft);
        $rvdRight = $mathService::filter($rvdRight);


        return $this->render('chart/index.html.twig', [
            'labels' => $labels,
            't4Right' => $t4Right,
            't4Left' => $t4Left,
            'alfaLeft' => $alfaRudLeft,
            'alfaRight' => $alfaRudRight,
            'rndLeft' => $rndLeft,
            'rndRight' => $rndRight,
            'rvdLeft' => $rvdLeft,
            'rvdRight' => $rvdRight,
            'departure_id' => $departure_id,
        ]);
    }
}
