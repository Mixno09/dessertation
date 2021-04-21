<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\FlightInformationRepository;
use Knp\Component\Pager\Event\Subscriber\Paginate\Callback\CallbackPagination;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AirplanesController extends AbstractController
{
    private FlightInformationRepository $repository;
    private PaginatorInterface $paginator;

    public function __construct(FlightInformationRepository $repository, PaginatorInterface $paginator)
    {
        $this->repository = $repository;
        $this->paginator = $paginator;
    }

    /**
     * @Route("/airplanes", name="airplanes", methods={"GET"})
     */
    public function index(Request $request): Response
    {
        $page = $request->query->getInt('page', 1);

        return $this->render('index/airplanes.html.twig', [
            'pagination' => $this->createPagination($page, 6),
        ]);
    }

    private function createPagination(int $page, int $limit): PaginationInterface
    {
        $target = new CallbackPagination(
            fn() => $this->repository->getCountUniqueAirplane(),
            fn($offset, $limit) => $this->repository->itemsAirplane($offset, $limit)
        );

        return $this->paginator->paginate($target, $page, $limit);
    }
}