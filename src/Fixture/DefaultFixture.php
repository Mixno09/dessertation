<?php

declare(strict_types=1);

namespace App\Fixture;

use Doctrine\DBAL\Connection;
use Sylius\Bundle\FixturesBundle\Fixture\AbstractFixture;
use Sylius\Bundle\FixturesBundle\Fixture\FixtureInterface;

class DefaultFixture extends AbstractFixture implements FixtureInterface
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function load(array $options): void
    {
        $sql = 'DELETE FROM flight_information';
        $this->connection->executeQuery($sql);

        $sql = 'DELETE FROM departures';
        $this->connection->executeQuery($sql);

        $sql = file_get_contents(__DIR__ . '/sql/departures.sql');
        $this->connection->executeQuery($sql);

        $sql = file_get_contents(__DIR__ . '/sql/flight_information.sql');
        $this->connection->executeQuery($sql);
    }

    public function getName(): string
    {
        return 'default';
    }
}