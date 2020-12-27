<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\FlightInformationPaginationFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class IndexController extends AbstractController
{
    private FlightInformationPaginationFactory $paginationFactory;

    public function __construct(FlightInformationPaginationFactory $paginationFactory)
    {
        $this->paginationFactory = $paginationFactory;
    }

    /**
     * @Route("/", name="main", methods="GET")
     * @param Request $request
     * @return Response
     */
    public function main(Request $request): Response
    {
        $page = $request->query->getInt('page', 1);
        $pagination = $this->paginationFactory->create($page, 6);

        return $this->render('index/index.html.twig', [
            'pagination' => $pagination,
        ]);

    }
}