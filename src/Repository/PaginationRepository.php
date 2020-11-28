<?php

declare(strict_types=1);

namespace App\Repository;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\FetchMode;
use Knp\Component\Pager\Event\Subscriber\Paginate\Callback\CallbackPagination;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

final class PaginationRepository
{
    /**
     * @var \Doctrine\DBAL\Connection
     */
    private $connection;

    /**
     * @var PaginatorInterface
     */
    private $paginationInterface;
    /**
     * IndexController constructor.
     * @param \Doctrine\DBAL\Connection $connection
     * @param \Knp\Component\Pager\PaginatorInterface $paginationInterface
     */
    public function __construct(Connection $connection, PaginatorInterface $paginationInterface)
    {
        $this->connection = $connection;
        $this->paginationInterface = $paginationInterface;
    }

    public function paginate(int $page, int $limit): PaginationInterface
    {
        $count = function () {
            $queryBuilder = $this->connection->createQueryBuilder();
            $query = $queryBuilder
                ->select('*')
                ->from('departures');
            $statement = $query->execute();
            return $statement->rowCount();
        };

        $items = function ($offset, $limit) {
            $queryBuilder = $this->connection->createQueryBuilder();
            $query = $queryBuilder
                ->select('*')
                ->from('departures')
                ->setFirstResult($offset)
                ->setMaxResults($limit);
            $statement = $query->execute();
            return $statement->fetchAll(FetchMode::ASSOCIATIVE);
        };

        $target = new CallbackPagination($count, $items);

        return $this->paginationInterface->paginate($target, $page, $limit);
    }
}