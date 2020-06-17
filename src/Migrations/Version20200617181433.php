<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200617181433 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE flight_information (id INT UNSIGNED AUTO_INCREMENT NOT NULL COMMENT \'первичный ключ\', airplane INT UNSIGNED NOT NULL, date DATE NOT NULL, departure INT UNSIGNED NOT NULL COMMENT \'номер вылета\', time INT UNSIGNED NOT NULL, t4_right INT NOT NULL, t4_left INT NOT NULL, alfa_rud_left NUMERIC(4, 1) UNSIGNED NOT NULL, alfa_rud_right NUMERIC(4, 1) UNSIGNED NOT NULL, rnd_left NUMERIC(4, 1) UNSIGNED NOT NULL, rvd_left NUMERIC(4, 1) UNSIGNED NOT NULL, rnd_right NUMERIC(4, 1) UNSIGNED NOT NULL, rvd_right NUMERIC(4, 1) UNSIGNED NOT NULL, UNIQUE INDEX flight_information_airplane_date_departure_time_uindex (airplane, date, departure, time), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'данные вылета\' ');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE flight_information');
    }
}
