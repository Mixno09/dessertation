<?php

declare(strict_types=1);

namespace App\Service;

use App\Repository\FlightInformationRepository;
use Knp\Component\Pager\Event\Subscriber\Paginate\Callback\CallbackPagination;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

class FlightInformationPaginationFactory
{
    private FlightInformationRepository $repository;
    private PaginatorInterface $paginationInterface;

    public function __construct(FlightInformationRepository $repository, PaginatorInterface $paginationInterface)
    {
        $this->repository = $repository;
        $this->paginationInterface = $paginationInterface;
    }

    public function create(int $page, int $limit): PaginationInterface
    {
        $count = function () {
            return $this->repository->count();
        };

        $items = function ($offset, $limit) {
            return $this->repository->items($offset, $limit);
        };

        $target = new CallbackPagination($count, $items);

        return $this->paginationInterface->paginate($target, $page, $limit);
    }
}