<?php

namespace App\Controller;

use App\Repository\FlightInformationRepository;
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
     * @Route("/flight-informations/{slug}/chart", name="flight_information", methods="GET")
     */
    public function index(string $slug): Response
    {
        $leftEngineParameters = $this->repository->findLeftEngineParametersBySlug($slug);
        $rightEngineParameters = $this->repository->findRightEngineParametersBySlug($slug);
        if (count($leftEngineParameters) === 0 || count($rightEngineParameters) === 0) {
            throw $this->createNotFoundException('Данных не существует.');
        }

        $time = [];
        $t4ValueLeft = [];
        $rudValueLeft = [];
        $rndValueLeft = [];
        $rvdValueLeft = [];
        foreach ($leftEngineParameters as $parameter) {
            $time[] = $parameter->getTime();
            $t4ValueLeft[] = $parameter->getT4();
            $rudValueLeft[] = $parameter->getAlfaRUD();
            $rndValueLeft[] = $parameter->getRnd();
            $rvdValueLeft[] = $parameter->getRvd();
        }

        $t4ValueRight = [];
        $rudValueRight = [];
        $rndValueRight = [];
        $rvdValueRight = [];
        foreach ($rightEngineParameters as $parameter) {
            $t4ValueRight[] = $parameter->getT4();
            $rudValueRight[] = $parameter->getAlfaRUD();
            $rndValueRight[] = $parameter->getRnd();
            $rvdValueRight[] = $parameter->getRvd();
        }

        return $this->render('chart/flight_information.html.twig', [
            'time' => $time,
            't4ValueLeft' => $t4ValueLeft,
            'rudValueLeft' => $rudValueLeft,
            'rndValueLeft' => $rndValueLeft,
            'rvdValueLeft' => $rvdValueLeft,
            't4ValueRight' => $t4ValueRight,
            'rudValueRight' => $rudValueRight,
            'rndValueRight' => $rndValueRight,
            'rvdValueRight' => $rvdValueRight,
        ]);
    }
}
