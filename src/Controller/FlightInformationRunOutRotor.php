<?php

declare(strict_types=1);

namespace App\Controller;

use App\UseCase\Query\RunOutListHandler;
use App\UseCase\Query\RunOutListQuery;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FlightInformationRunOutRotor extends AbstractController
{
    private RunOutListHandler $handler;

    public function __construct(RunOutListHandler $handler)
    {
        $this->handler = $handler;
    }

    /**
     * @Route("/select-rotor", name="select_rotor", methods={"GET"})
     */
    public function index(Request $request): Response
    {
        $query = new RunOutListQuery();
        $query->page = $request->query->getInt('page', 1);
        $query->limit = 6;
        $pagination = $this->handler->handle($query);

        return $this->render('index/select_rotor.html.twig', [
            'pagination' => $pagination,
        ]);
    }
}