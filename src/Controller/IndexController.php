<?php

declare(strict_types=1);

namespace App\Controller;

use App\UseCase\Query\FlightInformationListHandler;
use App\UseCase\Query\FlightInformationListQuery;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    private FlightInformationListHandler $handler;

    public function __construct(FlightInformationListHandler $handler)
    {
        $this->handler = $handler;
    }

    /**
     * @Route("/", name="main", methods="GET")
     */
    public function main(Request $request): Response
    {
        $query = new FlightInformationListQuery();
        $query->page = $request->query->getInt('page', 1);
        $query->limit = 6;
        $flightInformationList = $this->handler->handle($query);

        return $this->render('index/index.html.twig', [
            'pagination' => $flightInformationList->pagination,
        ]);

    }
}