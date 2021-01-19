<?php

declare(strict_types=1);

namespace App\UseCase\Query;

use App\Fetcher\AirplaneFetcher;
use Knp\Component\Pager\Event\Subscriber\Paginate\Callback\CallbackPagination;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

class RunOutListHandler
{
    private AirplaneFetcher $fetcher;
    private PaginatorInterface $paginator;

    public function __construct(AirplaneFetcher $fetcher, PaginatorInterface $paginator)
    {
        $this->fetcher = $fetcher;
        $this->paginator = $paginator;
    }

    public function handle(RunOutListQuery $query): PaginationInterface
    {
        $target = new CallbackPagination(
            fn() => $this->fetcher->count(),
            fn($offset, $limit) => $this->fetcher->items($offset, $limit)
        );

        return $this->paginator->paginate($target, $query->page, $query->limit);
    }
}