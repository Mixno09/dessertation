<?php

declare(strict_types=1);

namespace App\UseCase\Query;

use App\Fetcher\AirplaneFetcher;
use App\ViewModel\AirplaneList\Airplane;
use App\ViewModel\AirplaneList\AirplaneList;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
use Knp\Component\Pager\Event\Subscriber\Paginate\Callback\CallbackPagination;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

class RunOutListHandler
{
    private EntityManagerInterface $entityManager;
    private PaginatorInterface $paginator;

    public function __construct(EntityManagerInterface $entityManager, PaginatorInterface $paginator)
    {
        $this->entityManager = $entityManager;
        $this->paginator = $paginator;
    }

    public function handle(RunOutListQuery $query): AirplaneList
    {
        $target = new CallbackPagination(
            fn() => $this->count(),
            fn($offset, $limit) => $this->items($offset, $limit)
        );

        $airplaneList = new AirplaneList();
        $airplaneList->pagination = $this->paginator->paginate($target, $query->page, $query->limit);

        return $airplaneList;
    }

    private function count(): int
    {
        return (int) $this->entityManager
            ->createQuery('SELECT COUNT(DISTINCT f.id.airplane) FROM App\Entity\FlightInformation f')
            ->getSingleScalarResult();
    }

    private function items(int $offset, int $limit): array
    {
        $rows = $this->entityManager
            ->createQuery('SELECT DISTINCT f.id.airplane FROM App\Entity\FlightInformation f ORDER BY f.id.airplane')
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->getArrayResult();

        $items = [];
        foreach ($rows as $row) {
            $item = new Airplane();
            $item->id = $row['id.airplane'];
            $items[] = $item;
        }

        return $items;
    }
}