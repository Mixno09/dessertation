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
        $statement = $this->connection->executeQuery('SELECT time, alfa_rud_left, alfa_rud_right FROM flight_information WHERE departure_id = :id', [':id' => $id]);
        $data = $statement->fetchAll();
        $alfaRudLeft = [];
        $alfaRudRight = [];
        $labels = [];
        foreach ($data as $information) {
            $time = $information['time'];
            $alfaRudLeft[$time] = $information['alfa_rud_left'];
            $alfaRudRight[$time] = $information['alfa_rud_right'];
            $labels[] = $time;
        }
        $filterAlfaRudLeft = array_reverse(MathService::filter($alfaRudLeft), true);
        $filterAlfaRudRight = array_reverse(MathService::filter($alfaRudRight), true);
        foreach ($filterAlfaRudLeft as $key => $value) {
            if ($filterAlfaRudLeft[$key] >= $filterAlfaRudLeft[$key - 1]) {
                continue;
            }
            $timeStop = $key;
            break;
        }
        $alfaRudLeft = array_reverse($alfaRudLeft);
        $alfaRudRight = array_reverse($alfaRudRight);
        return $this->render('chart/test.html.twig', [
            'count' => array_reverse($labels),
            'alfa_rud_left' => $alfaRudLeft,
            'filter_alfa_rud_left' => array_values($filterAlfaRudLeft),
            'alfa_rud_right' => $alfaRudRight,
            'filter_alfa_rud_right' => array_values($filterAlfaRudRight),
        ]);
    }
}