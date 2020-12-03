<?php

declare(strict_types=1);

namespace App\Repository;

use App\Services\MathService;
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

    public function findTimeRndLeftRotor(int $id): array
    {
        $statement = $this->connection->executeQuery('SELECT time, alfa_rud_left FROM flight_information WHERE departure_id = :id', [':id' => $id]);
        $data = $statement->fetchAll();

        $timeStop = $this->getEngineStopTime($data);

        $statement = $this->connection->executeQuery(
            'SELECT time, rnd_left FROM flight_information WHERE departure_id = :id AND time >= :timeStop AND rnd_left >= :rnd',
            [
                ':id' => $id,
                ':timeStop' => $timeStop,
                ':rnd' => 10,
            ]);
        $rndLefts = $statement->fetchAll();

        $valueRndLeft = [];
        $valueTimeRndLeft = [];
        foreach ($rndLefts as $rndLeft) {
            $valueRndLeft[] = $rndLeft['rnd_left'];
            $valueTimeRndLeft[] = $rndLeft['time'];
        }
        return $this->sortTimeByParams($valueTimeRndLeft, $valueRndLeft);
    }

    public function findTimeRndRightRotor(int $id): array
    {
        $statement = $this->connection->executeQuery('SELECT time, alfa_rud_right FROM flight_information WHERE departure_id = :id', [':id' => $id]);
        $data = $statement->fetchAll();

        $timeStop = $this->getEngineStopTime($data);

        $statement = $this->connection->executeQuery(
            'SELECT time, rnd_right FROM flight_information WHERE departure_id = :id AND time >= :timeStop AND rnd_right >= :rnd',
            [
                ':id' => $id,
                ':timeStop' => $timeStop,
                ':rnd' => 10,
            ]);
        $rndRights = $statement->fetchAll();

        $valueRndRight = [];
        $valueTimeRndRight = [];
        foreach ($rndRights as $rndRight) {
            $valueRndRight[] = $rndRight['rnd_right'];
            $valueTimeRndRight[] = $rndRight['time'];
        }
        return $this->sortTimeByParams($valueTimeRndRight, $valueRndRight);
    }

    public function findTimeRvdLeftRotor(int $id): array
    {
        $statement = $this->connection->executeQuery('SELECT time, alfa_rud_left FROM flight_information WHERE departure_id = :id', [':id' => $id]);
        $data = $statement->fetchAll();

        $timeStop = $this->getEngineStopTime($data);

        $statement = $this->connection->executeQuery(
            'SELECT rvd_left, time FROM flight_information WHERE departure_id = :id AND time >= :timeStop AND rvd_left >= :rvd',
            [
                ':id' => $id,
                ':timeStop' => $timeStop,
                ':rvd' => 10,
            ]);
        $rvdLefts = $statement->fetchAll();

        $valueRvdLeft = [];
        $valueTimeRvdLeft = [];
        foreach ($rvdLefts as $rvdLeft) {
            $valueRvdLeft[] = $rvdLeft['rvd_left'];
            $valueTimeRvdLeft[] = $rvdLeft['time'];
        }
        return $this->sortTimeByParams($valueTimeRvdLeft, $valueRvdLeft);
    }

    public function findTimeRvdRightRotor(int $id): array
    {
        $statement = $this->connection->executeQuery('SELECT time, alfa_rud_right FROM flight_information WHERE departure_id = :id', [':id' => $id]);
        $data = $statement->fetchAll();

        $timeStop = $this->getEngineStopTime($data);

        $statement = $this->connection->executeQuery(
            'SELECT rvd_right, time FROM flight_information WHERE departure_id = :id AND time >= :timeStop AND rvd_right >= :rvd',
            [
                ':id' => $id,
                ':timeStop' => $timeStop,
                ':rvd' => 10,
            ]);
        $rvdRights = $statement->fetchAll();

        $valueRvdRight = [];
        $valueTimeRvdRight = [];
        foreach ($rvdRights as $rvdRight) {
            $valueRvdRight[] = $rvdRight['rvd_right'];
            $valueTimeRvdRight[] = $rvdRight['time'];
        }
        return $this->sortTimeByParams($valueTimeRvdRight, $valueRvdRight);
    }

    private function sortTimeByParams(array $times, array $values): array
    {
        $timeSort = [];
        if (count($times) <= 0) {
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

    public function findInformationById(int $id): array
    {
        $statement = $this->connection->executeQuery('SELECT * FROM flight_information WHERE departure_id = :id ORDER BY time', [':id' => $id]);
        return $statement->fetchAll();
    }

    private function getEngineStopTime(array $data): int
    {
        $alfaRud = [];
        foreach ($data as $information) {
            $time = $information['time'];
            if (array_key_exists('alfa_rud_left', $information)) {
                $alfaRud[$time] = $information['alfa_rud_left'];
            } else {
                $alfaRud[$time] = $information['alfa_rud_right'];
            }
        }
        $filterAlfaRud = array_reverse(MathService::filter($alfaRud), true);
        foreach ($filterAlfaRud as $key => $value) {
            if ($filterAlfaRud[$key] >= $filterAlfaRud[$key - 1]) {
                continue;
            }
            break;
        }
        return $key;
    }
}