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

        $sql = 'DELETE FROM engine_parameter_collection';
        $this->connection->executeQuery($sql);

        $sql = 'DELETE FROM engine_parameter_collections_engine_parameters';
        $this->connection->executeQuery($sql);

        $sql = 'DELETE FROM engine_parameter_collections_mutual_parameters';
        $this->connection->executeQuery($sql);

        $sql = 'DELETE FROM mutual_parameter';
        $this->connection->executeQuery($sql);

        $sql = 'DELETE FROM engine_parameter';
        $this->connection->executeQuery($sql);

        $sql = file_get_contents(__DIR__ . '/sql/engine_parameter_collection.sql');
        $this->connection->executeQuery($sql);

        $sql = file_get_contents(__DIR__ . '/sql/flight_information.sql');
        $this->connection->executeQuery($sql);

        $sql = file_get_contents(__DIR__ . '/sql/engine_parameter.sql');
        $this->connection->executeQuery($sql);

        $sql = file_get_contents(__DIR__ . '/sql/engine_parameter_collections_engine_parameters.sql');
        $this->connection->executeQuery($sql);

        $sql = file_get_contents(__DIR__ . '/sql/mutual_parameter.sql');
        $this->connection->executeQuery($sql);

        $sql = file_get_contents(__DIR__ . '/sql/engine_parameter_collections_mutual_parameters.sql');
        $this->connection->executeQuery($sql);

    }

    public function getName(): string
    {
        return 'default';
    }
}
