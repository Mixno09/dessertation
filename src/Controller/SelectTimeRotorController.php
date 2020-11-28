<?php

namespace App\Controller;

use App\Repository\AirplaneRepository;
use Knp\Component\Pager\Event\Subscriber\Paginate\Callback\CallbackPagination;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SelectTimeRotorController extends AbstractController
{
    private AirplaneRepository $repository;

    private PaginatorInterface $paginator;

    public function __construct(AirplaneRepository $repository, PaginatorInterface $paginator)
    {
        $this->repository = $repository;
        $this->paginator = $paginator;
    }

    /**
     * @Route("/selecttimerotor", name="select_time_rotor", methods={"GET"})
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $page = $request->query->getInt('page', 1);
        $target = new CallbackPagination([$this->repository, 'getCount'], [$this->repository, 'getItems']);

        $pagination = $this->paginator->paginate($target, $page, 10);

        return $this->render('index/select_time_rotor.html.twig', [
            'pagination' => $pagination,
        ]);
    }
}
