<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\FlightInformationPaginationFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FlightInformationRunOutRotor extends AbstractController
{

    private FlightInformationPaginationFactory $paginationFactory;

    public function __construct(FlightInformationPaginationFactory $paginationFactory)
    {
        $this->paginationFactory = $paginationFactory;
    }

    /**
     * @Route("/select-rotor", name="select_rotor", methods={"GET"})
     */
    public function index(Request $request): Response
    {
        $page = $request->query->getInt('page', 1);
        $pagination = $this->paginationFactory->create($page,6);

        return $this->render('index/select_rotor.html.twig', [
            'pagination' => $pagination,
        ]);
    }
}