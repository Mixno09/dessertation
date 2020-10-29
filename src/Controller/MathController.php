<?php

declare(strict_types=1);

namespace App\Controller;

use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


final class MathController extends AbstractController
{
    /**
     * @var \Doctrine\DBAL\Connection
     */
    private $connection;

    /**
     * MathController constructor.
     * @param \Doctrine\DBAL\Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @Route("/math", name="math", methods={"GET"})
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function math(): Response
    {
        $queryBuilder = $this->connection->createQueryBuilder();
        $query = $queryBuilder
            ->select('rvd_left', 'rvd_right')
            ->from('flight_information')
            ->where('alfa_rud_left>43');
//            ->andWhere('alfa_rud_right>40');
        $statement = $query->execute();
        $params = $statement->fetchAll();

        $rvdLeft = [];
        $rvdRight = [];

        foreach ($params as $param) {
            $rvdLeft[] = $param['rvd_left'];
            $rvdRight[] = $param['rvd_right'];
        }
//        var_dump($params, $rvdLeft, $rvdRight);
//        die();
        return $this->render('mathcalc/mathcalc.html.twig', [
            'rvd_left' => $rvdLeft,
            'rvd_right' => $rvdRight,
        ]);
    }
}