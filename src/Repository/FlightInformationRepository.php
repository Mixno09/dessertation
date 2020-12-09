<?php

declare(strict_types=1);

namespace App\Repository;

use Doctrine\DBAL\Connection;

final class FlightInformationRepository
{
    private Connection $connection;

    /**
     * FlightInformationRepository constructor.
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function findAlfaRudLeft(int $departureId): array
    {
        $statement = $this->connection->executeQuery('SELECT time, alfa_rud_left FROM flight_information WHERE departure_id = :id', [':id' => $departureId]);
        $data = $statement->fetchAll();
        $alfaRudLeft = [];
        foreach ($data as $information) {
            $time = $information['time'];
            $alfaRudLeft[$time] = $information['alfa_rud_left'];
        }
        return $alfaRudLeft;
    }

    public function findAlfaRudRight(int $departureId): array
    {
        $statement = $this->connection->executeQuery('SELECT time, alfa_rud_right FROM flight_information WHERE departure_id = :id', [':id' => $departureId]);
        $data = $statement->fetchAll();
        $alfaRudRight = [];
        foreach ($data as $information) {
            $time = $information['time'];
            $alfaRudRight[$time] = $information['alfa_rud_right'];
        }
        return $alfaRudRight;
    }

    public function findRevsRndLeft(int $departureId): array
    {
        $statement = $this->connection->executeQuery('SELECT time, rnd_left FROM flight_information WHERE departure_id = :id', [':id' => $departureId]);
        $data = $statement->fetchAll();
        $revsRndLeft = [];
        foreach ($data as $information) {
            $time = $information['time'];
            $revsRndLeft[$time] = $information['rnd_left'];
        }
        return $revsRndLeft;
    }

    public function findRevsRvdLeft(int $departureId): array
    {
        $statement = $this->connection->executeQuery('SELECT time, rvd_left FROM flight_information WHERE departure_id = :id', [':id' => $departureId]);
        $data = $statement->fetchAll();
        $revsRvdLeft = [];
        foreach ($data as $information) {
            $time = $information['time'];
            $revsRvdLeft[$time] = $information['rvd_left'];
        }
        return $revsRvdLeft;
    }

    public function findRevsRndRight(int $departureId): array
    {
        $statement = $this->connection->executeQuery('SELECT time, rnd_right FROM flight_information WHERE departure_id = :id', [':id' => $departureId]);
        $data = $statement->fetchAll();
        $revsRndRight = [];
        foreach ($data as $information) {
            $time = $information['time'];
            $revsRndRight[$time] = $information['rnd_right'];
        }
        return $revsRndRight;
    }

    public function findRevsRvdRight(int $departureId): array
    {
        $statement = $this->connection->executeQuery('SELECT time, rvd_right FROM flight_information WHERE departure_id = :id', [':id' => $departureId]);
        $data = $statement->fetchAll();
        $revsRvdRight = [];
        foreach ($data as $information) {
            $time = $information['time'];
            $revsRvdRight[$time] = $information['rvd_right'];
        }
        return $revsRvdRight;
    }

    public function findInformationById(int $id): array
    {
        $statement = $this->connection->executeQuery('SELECT * FROM flight_information WHERE departure_id = :id ORDER BY time', [':id' => $id]);
        return $statement->fetchAll();
    }
}