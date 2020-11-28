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
     * @Route("/departure/{id}", name="departure", methods="GET")
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(int $id): Response
    {
        $queryBuilder = $this->connection->createQueryBuilder();
        $query = $queryBuilder
            ->select('*')
            ->from('flight_information')
            ->where('departure_id = ' . $queryBuilder->createPositionalParameter($id))
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
        $departure_id = '';
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
            $departure_id = $param['departure_id'];
        }

        $t4Right = self::filter($t4Right);
        $t4Left = self::filter($t4Left);
        $alfaRudLeft = self::filter($alfaRudLeft);
        $alfaRudRight = self::filter($alfaRudRight);
        $rndLeft = self::filter($rndLeft);
        $rndRight = self::filter($rndRight);
        $rvdLeft = self::filter($rvdLeft);
        $rvdRight = self::filter($rvdRight);


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
            'departure_id' => $departure_id,
        ]);
    }

    public static function medianFilter(...$values)
    {
        sort($values);
        if (count($values) % 2 === 0) {
            $rightIndex = count($values) / 2;
            $leftIndex = $rightIndex - 1;
            $right = $values[$rightIndex];
            $left = $values[$leftIndex];
            $result = ($right + $left) / 2;
        } else {
            $lastIndex = count($values) - 1;
            $middleIndex = $lastIndex / 2;
            $result = $values[$middleIndex];
        }
        return $result;
    }

    public static function filter(array $data): array
    {
        $result = [];
        for ($key = 0; $key < count($data); $key++) {
            $values = [];
            $offset = 0;
            $leftIndex = $key - $offset;
            while ($leftIndex < 0) {
                $values[] = $data[$key];
                $leftIndex++;
            }
            $rightIndex = $key + $offset;
            while ($rightIndex >= count($data)) {
                $values[] = $data[$key];
                $rightIndex--;
            }
            for ($i = $leftIndex; $i <= $rightIndex; $i++) {
                $values[] = $data[$i];
            }
            $middle = self::medianFilter(...$values);
            $result[$key] = $middle;
        }
        return $result;
    }
}
