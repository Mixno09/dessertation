<?php

declare(strict_types=1);

namespace App\Controller;

use App\Services\MathService;
use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    private Connection $connection;

    /**
     * TestController constructor.
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @Route("/test/{id}", name="test", methods={"GET"})
     * @param int $id
     * @return Response
     */
    public function test(int $id): Response
    {
        $statement = $this->connection->executeQuery('SELECT time, alfa_rud_right FROM flight_information WHERE departure_id = :id', [':id' => $id]);
        $data = $statement->fetchAll();
        $alfaRudRight = [];
        $labels = [];
        foreach ($data as $information) {
            $time = $information['time'];
            $alfaRudRight[$time] = $information['alfa_rud_right'];
            $labels[] = $time;
        }
        $filterAlfaRudRight = array_reverse(MathService::filter($alfaRudRight), true);
        foreach ($filterAlfaRudRight as $key => $value) {
            if ($filterAlfaRudRight[$key] >= $filterAlfaRudRight[$key - 1]) {
                continue;
            }
            $timeStopLeft = $key;
            break;
        }
        $alfaRudRight = array_reverse($alfaRudRight);

        $statement = $this->connection->executeQuery(
            'SELECT rnd_right, time FROM flight_information WHERE departure_id = :id AND time >= :timeStopLeft AND rnd_right >= :rndRight',
            [
                ':id' => $id,
                ':timeStopLeft' => $timeStopLeft,
                ':rndRight' => 10,
            ]);

        $rndLefts = $statement->fetchAll();

        $valueTimeRndLeft = [];
        $valueRndLeft = [];
        foreach ($rndLefts as $rndLeft) {
            $valueRndLeft[] = $rndLeft['rnd_right'];
            $valueTimeRndLeft[] = $rndLeft['time'];
        }
        $inf = $this->sortTimeByParams($valueTimeRndLeft, $valueRndLeft);

        return $this->render('chart/test.html.twig', [
            'count' => array_reverse($labels),
            'alfa_rud_left' => $alfaRudRight,
            'filter_alfa_rud_left' => array_values($filterAlfaRudRight),
            'alfa_rud_right' => $alfaRudRight,
            'filter_alfa_rud_right' => array_values($filterAlfaRudRight),
        ]);
    }

    private function sortTimeByParams(array $times, array $values): array
    {
        $timeSort = [];
        if (count($times) <= 5) {
            return $timeSort;
        }
        $timeAndValue[] = $values[0];
        for ($i = 1; $i < count($values); $i++) {
            if ($values[$i] >= 10 && $values[$i] < $values[$i - 1]) {
                $timeAndValue[] = $values[$i];
                continue;
            }
            break;
        }
        return $timeAndValue;
    }
}