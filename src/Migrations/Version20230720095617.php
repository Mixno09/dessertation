<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230720095617 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE engine_parameter_collections_mutual_parameters (engine_parameter_collection INT NOT NULL, mutual_parameter INT NOT NULL, INDEX IDX_2A8D8CE2A26E46FD (engine_parameter_collection), UNIQUE INDEX UNIQ_2A8D8CE2D0CB6F86 (mutual_parameter), PRIMARY KEY(engine_parameter_collection, mutual_parameter)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mutual_parameter (id INT AUTO_INCREMENT NOT NULL, time INT UNSIGNED NOT NULL, distribution_density_t4_rnd NUMERIC(11, 9) NOT NULL, distribution_density_t4_rvd NUMERIC(11, 9) NOT NULL, distribution_density_rnd_rvd NUMERIC(11, 9) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, login VARCHAR(50) NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649AA08CB10 (login), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE engine_parameter_collections_mutual_parameters ADD CONSTRAINT FK_2A8D8CE2A26E46FD FOREIGN KEY (engine_parameter_collection) REFERENCES engine_parameter_collection (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE engine_parameter_collections_mutual_parameters ADD CONSTRAINT FK_2A8D8CE2D0CB6F86 FOREIGN KEY (mutual_parameter) REFERENCES mutual_parameter (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE engine_parameter_collection ADD calc_parameter_error TINYINT(1) NOT NULL, ADD calc_parameter_correlation_coefficient_for_t4_rnd NUMERIC(8, 4) NOT NULL, ADD calc_parameter_correlation_coefficient_for_t4_rvd NUMERIC(8, 4) NOT NULL, ADD calc_parameter_correlation_coefficient_for_rnd_rvd NUMERIC(8, 4) NOT NULL, ADD calc_parameter_t4_average NUMERIC(6, 3) NOT NULL, ADD calc_parameter_t4_sample_variance NUMERIC(8, 3) NOT NULL, ADD calc_parameter_t4_root_mean_square_deviation NUMERIC(6, 3) NOT NULL, ADD calc_parameter_t4_coefficient_of_variation NUMERIC(6, 3) NOT NULL, ADD calc_parameter_t4_standard_error_of_the_mean NUMERIC(6, 3) NOT NULL, ADD calc_parameter_t4_number_of_degrees_of_freedom NUMERIC(6, 3) NOT NULL, ADD calc_parameter_rnd_average NUMERIC(6, 3) NOT NULL, ADD calc_parameter_rnd_sample_variance NUMERIC(8, 3) NOT NULL, ADD calc_parameter_rnd_root_mean_square_deviation NUMERIC(6, 3) NOT NULL, ADD calc_parameter_rnd_coefficient_of_variation NUMERIC(6, 3) NOT NULL, ADD calc_parameter_rnd_standard_error_of_the_mean NUMERIC(6, 3) NOT NULL, ADD calc_parameter_rnd_number_of_degrees_of_freedom NUMERIC(6, 3) NOT NULL, ADD calc_parameter_rvd_average NUMERIC(6, 3) NOT NULL, ADD calc_parameter_rvd_sample_variance NUMERIC(8, 3) NOT NULL, ADD calc_parameter_rvd_root_mean_square_deviation NUMERIC(6, 3) NOT NULL, ADD calc_parameter_rvd_coefficient_of_variation NUMERIC(6, 3) NOT NULL, ADD calc_parameter_rvd_standard_error_of_the_mean NUMERIC(6, 3) NOT NULL, ADD calc_parameter_rvd_number_of_degrees_of_freedom NUMERIC(6, 3) NOT NULL, DROP average_parameter_t4, DROP average_parameter_rnd, DROP average_parameter_rvd');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE engine_parameter_collections_mutual_parameters DROP FOREIGN KEY FK_2A8D8CE2D0CB6F86');
        $this->addSql('DROP TABLE engine_parameter_collections_mutual_parameters');
        $this->addSql('DROP TABLE mutual_parameter');
        $this->addSql('DROP TABLE user');
        $this->addSql('ALTER TABLE engine_parameter_collection ADD average_parameter_t4 NUMERIC(5, 1) NOT NULL, ADD average_parameter_rnd NUMERIC(5, 1) NOT NULL, ADD average_parameter_rvd NUMERIC(5, 1) NOT NULL, DROP calc_parameter_error, DROP calc_parameter_correlation_coefficient_for_t4_rnd, DROP calc_parameter_correlation_coefficient_for_t4_rvd, DROP calc_parameter_correlation_coefficient_for_rnd_rvd, DROP calc_parameter_t4_average, DROP calc_parameter_t4_sample_variance, DROP calc_parameter_t4_root_mean_square_deviation, DROP calc_parameter_t4_coefficient_of_variation, DROP calc_parameter_t4_standard_error_of_the_mean, DROP calc_parameter_t4_number_of_degrees_of_freedom, DROP calc_parameter_rnd_average, DROP calc_parameter_rnd_sample_variance, DROP calc_parameter_rnd_root_mean_square_deviation, DROP calc_parameter_rnd_coefficient_of_variation, DROP calc_parameter_rnd_standard_error_of_the_mean, DROP calc_parameter_rnd_number_of_degrees_of_freedom, DROP calc_parameter_rvd_average, DROP calc_parameter_rvd_sample_variance, DROP calc_parameter_rvd_root_mean_square_deviation, DROP calc_parameter_rvd_coefficient_of_variation, DROP calc_parameter_rvd_standard_error_of_the_mean, DROP calc_parameter_rvd_number_of_degrees_of_freedom');
    }
}
