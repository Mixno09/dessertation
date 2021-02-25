<?php

declare(strict_types=1);

namespace App\Controller;

use App\Fetcher\FlightInformationFetcher;
use Knp\Component\Pager\Event\Subscriber\Paginate\Callback\CallbackPagination;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    private FlightInformationFetcher $fetcher;
    private PaginatorInterface $paginator;

    public function __construct(FlightInformationFetcher $fetcher, PaginatorInterface $paginator)
    {
        $this->fetcher = $fetcher;
        $this->paginator = $paginator;
    }

    /**
     * @Route("/", name="main", methods="GET")
     */
    public function main(Request $request): Response
    {
        $page = $request->query->getInt('page', 1);

        return $this->render('index/index.html.twig', [
            'pagination' => $this->createPagination($page, 6),
        ]);
    }

    private function createPagination(int $page, int $limit): PaginationInterface
    {
        $target = new CallbackPagination(
            fn() => $this->fetcher->count(),
            fn($offset, $limit) => $this->fetcher->items($offset, $limit)
        );

        return $this->paginator->paginate($target, $page, $limit);
    }
}