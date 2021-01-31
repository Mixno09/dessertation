<?php

declare(strict_types=1);

namespace App\UseCase\Query;

use App\Fetcher\AirplaneFetcher;
use App\ViewModel\RunOutList\RunOutList;
use Knp\Component\Pager\Event\Subscriber\Paginate\Callback\CallbackPagination;
use Knp\Component\Pager\PaginatorInterface;

class RunOutListHandler
{
    private AirplaneFetcher $airplaneFetcher;
    private PaginatorInterface $paginator;

    public function __construct(AirplaneFetcher $airplaneFetcher, PaginatorInterface $paginator)
    {
        $this->airplaneFetcher = $airplaneFetcher;
        $this->paginator = $paginator;
    }


    public function handle(RunOutListQuery $query): RunOutList
    {
        $target = new CallbackPagination(
            fn() => $this->airplaneFetcher->count(),
            fn($offset, $limit) => $this->airplaneFetcher->items($offset, $limit)
        );

        $airplaneList = new RunOutList();
        $airplaneList->pagination = $this->paginator->paginate($target, $query->page, $query->limit);

        return $airplaneList;
    }
}