<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210311122227 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE engine_parameter (id INT AUTO_INCREMENT NOT NULL, time INT UNSIGNED NOT NULL, t4 NUMERIC(5, 1) NOT NULL, alfa_rud NUMERIC(4, 1) NOT NULL, rnd NUMERIC(5, 1) UNSIGNED NOT NULL, rvd NUMERIC(5, 1) UNSIGNED NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE engine_parameter_collection (id INT AUTO_INCREMENT NOT NULL, average_parameter_t4 NUMERIC(5, 1) NOT NULL, average_parameter_rnd NUMERIC(5, 1) NOT NULL, average_parameter_rvd NUMERIC(5, 1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE engine_parameter_collections_engine_parameters (engine_parameter_collection INT NOT NULL, engine_parameter INT NOT NULL, INDEX IDX_D3E0618CA26E46FD (engine_parameter_collection), UNIQUE INDEX UNIQ_D3E0618C4F96E1A (engine_parameter), PRIMARY KEY(engine_parameter_collection, engine_parameter)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE engine_parameter_collections_engine_parameters ADD CONSTRAINT FK_D3E0618CA26E46FD FOREIGN KEY (engine_parameter_collection) REFERENCES engine_parameter_collection (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE engine_parameter_collections_engine_parameters ADD CONSTRAINT FK_D3E0618C4F96E1A FOREIGN KEY (engine_parameter) REFERENCES engine_parameter (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE engine_parameter_collections_engine_parameters DROP FOREIGN KEY FK_D3E0618C4F96E1A');
        $this->addSql('ALTER TABLE engine_parameter_collections_engine_parameters DROP FOREIGN KEY FK_D3E0618CA26E46FD');
        $this->addSql('DROP TABLE engine_parameter');
        $this->addSql('DROP TABLE engine_parameter_collection');
        $this->addSql('DROP TABLE engine_parameter_collections_engine_parameters');
    }
}
