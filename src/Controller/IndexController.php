<?php

declare(strict_types=1);

namespace App\Controller;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\FetchMode;
use Knp\Component\Pager\Event\Subscriber\Paginate\Callback\CallbackPagination;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class IndexController extends AbstractController
{
    /**
     * @var \Doctrine\DBAL\Connection
     */
    private $connection;

    /**
     * IndexController constructor.
     * @param \Doctrine\DBAL\Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @Route("/", name="index", methods="GET")
     * @param \Knp\Component\Pager\PaginatorInterface $paginator
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(PaginatorInterface $paginator): Response
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

        $pagination = $paginator->paginate($target, 1, 1);

        return $this->render('index/index.html.twig', [
            'pagination' => $pagination,
        ]);

    }
}