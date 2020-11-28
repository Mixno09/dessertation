<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\PaginationRepository;
use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class IndexController extends AbstractController
{
    /**
     * @var \Doctrine\DBAL\Connection
     */
    private $connection;

    /**
     * @var PaginationRepository
     */
    private $paginationRepository;

    /**
     * IndexController constructor.
     * @param \Doctrine\DBAL\Connection $connection
     * @param \App\Repository\PaginationRepository $paginationRepository
     */
    public function __construct(Connection $connection, PaginationRepository $paginationRepository)
    {
        $this->connection = $connection;
        $this->paginationRepository = $paginationRepository;
    }

    /**
     * @Route("/", name="index", methods="GET")
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request): Response
    {
        $page = $request->query->getInt('page', 1);
        $pagination = $this->paginationRepository->paginate($page, 6);

        return $this->render('index/index.html.twig', [
            'pagination' => $pagination,
        ]);

    }
}