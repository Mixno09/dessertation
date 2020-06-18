<?php

namespace App\Controller;

use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ChartController extends AbstractController
{
    /**
     * @var \Doctrine\DBAL\Connection
     */
    private $connection;

    /**
     * ChartController constructor.
     * @param \Doctrine\DBAL\Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @Route("/departure/{airplane}/{date}/{departure}", name="departure")
     * @param int $airplane
     * @param int $departure
     * @param string $date
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(int $airplane, string $date, int $departure): Response
    {
        $queryBuilder = $this->connection->createQueryBuilder();
        $query = $queryBuilder
            ->select('*')
            ->from('flight_information')
            ->where('airplane = ' . $queryBuilder->createPositionalParameter($airplane))
            ->andWhere('date = ' . $queryBuilder->createPositionalParameter($date))
            ->andWhere('departure = ' . $queryBuilder->createPositionalParameter($departure))
            ->orderBy('time');
        $statement = $query->execute();
        $params = $statement->fetchAll();

        $labels = [];
        $t4Right = [];
        $t4Left = [];
        $alfaRudLeft = [];
        $alfaRudRight = [];
        $rndLeft = [];
        $rndRight = [];
        $rvdLeft = [];
        $rvdRight = [];
        foreach ($params as $param) {
            $labels[] = $param['time'];
            $t4Right[] = $param['t4_right'];
            $t4Left[] = $param['t4_left'];
            $alfaRudLeft[] = $param['alfa_rud_left'];
            $alfaRudRight[] = $param['alfa_rud_right'];
            $rndLeft[] = $param['rnd_left'];
            $rndRight[] = $param['rnd_right'];
            $rvdLeft[] = $param['rvd_left'];
            $rvdRight[] = $param['rvd_right'];
        }

        return $this->render('chart/index.html.twig', [
            'labels' => $labels,
            't4Right' => $t4Right,
            't4Left' => $t4Left,
            'alfaLeft' => $alfaRudLeft,
            'alfaRight' => $alfaRudRight,
            'rndLeft' => $rndLeft,
            'rndRight' => $rndRight,
            'rvdLeft' => $rvdLeft,
            'rvdRight' => $rvdRight,
        ]);
    }
}
