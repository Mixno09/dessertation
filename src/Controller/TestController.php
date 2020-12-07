<?php

declare(strict_types=1);

namespace App\Controller;

use App\Domain\ChangeAlfaRUD;
use App\Domain\ChangeRevs;
use App\Repository\AirplaneRepository;
use App\Repository\FlightInformationRepository;
use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
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
     * @Route("/test/{id}", name="test", methods={"GET"})
     * @param int $id
     * @return Response
     */
    public function test(int $id): Response
    {
        $airplane = $this->airplaneRepository->findAirplanesById($id);
        foreach ($airplane as $data) {
            $departureId = (int) $data['id'];
            $alfaLeft[] = $this->flightInformationRepository->findAlfaRudLeft($departureId);
            $alfaRight = $this->flightInformationRepository->findAlfaRudRight($departureId);
            $revsRndLeft[] = $this->flightInformationRepository->findRevsRndLeft($departureId);
            $revsRvdLeft = $this->flightInformationRepository->findRevsRvdLeft($departureId);
            $revsRndRight = $this->flightInformationRepository->findRevsRndRight($departureId);
            $revsRvdRight = $this->flightInformationRepository->findRevsRvdRight($departureId);
            $changeAlfaRUD = new ChangeAlfaRUD($alfaLeft);
            $stopAlfaRUD = $changeAlfaRUD->stopTime();
            $changeRevs = new ChangeRevs($revsRndLeft);
            $stopRevs = $changeRevs->stopRevs($stopAlfaRUD);
        }

//        return $this->render('chart/test.html.twig', [
//            'count' => array_reverse($labels),
//            'alfa_rud_left' => $alfaRudRight,
//            'filter_alfa_rud_left' => array_values($filterAlfaRudRight),
//            'alfa_rud_right' => $alfaRudRight,
//            'filter_alfa_rud_right' => array_values($filterAlfaRudRight),
//        ]);
    }
}