<?php

declare(strict_types=1);

namespace App\UseCase\Query;

use App\Fetcher\FlightInformationFetcher;
use App\ViewModel\FlightInformationList\FlightInformationList;
use Knp\Component\Pager\Event\Subscriber\Paginate\Callback\CallbackPagination;
use Knp\Component\Pager\PaginatorInterface;

class FlightInformationListHandler
{
    private FlightInformationFetcher $flightInformationFetcher;
    private PaginatorInterface $paginator;

    public function __construct(FlightInformationFetcher $flightInformationFetcher, PaginatorInterface $paginator)
    {
        $this->flightInformationFetcher = $flightInformationFetcher;
        $this->paginator = $paginator;
    }

    public function handle(FlightInformationListQuery $query): FlightInformationList
    {
        $target = new CallbackPagination(
            fn() => $this->flightInformationFetcher->count(),
            fn($offset, $limit) => $this->flightInformationFetcher->items($offset, $limit)
        );

        $flightInformationList = new FlightInformationList();
        $flightInformationList->pagination = $this->paginator->paginate($target, $query->page, $query->limit);

        return $flightInformationList;
    }
}