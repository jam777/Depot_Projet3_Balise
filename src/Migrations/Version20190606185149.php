<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190606185149 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()
                ->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE spectacle (id INT AUTO_INCREMENT NOT NULL, theater_id_id INT NOT NULL, 
title VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, distribution LONGTEXT NOT NULL, 
mandatory_infos LONGTEXT DEFAULT NULL, image VARCHAR(255) NOT NULL, photo_credits VARCHAR(255) DEFAULT NULL, 
additional_infos LONGTEXT DEFAULT NULL, is_balise TINYINT(1) NOT NULL, offer_type SMALLINT DEFAULT NULL, 
mapado_link VARCHAR(255) DEFAULT NULL, base_rate DOUBLE PRECISION DEFAULT NULL, 
INDEX IDX_E55076F44ECC313E (theater_id_id), 
PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE param (id INT AUTO_INCREMENT NOT NULL, 
resale_coeff DOUBLE PRECISION NOT NULL, 
redistributed_coeff DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) 
DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, 
email VARCHAR(180) NOT NULL, theater_name VARCHAR(180) DEFAULT NULL, roles JSON NOT NULL, 
password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), 
PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE show_rate (id INT AUTO_INCREMENT NOT NULL, 
show_date_id INT NOT NULL, free_places_number INT DEFAULT NULL, discounted_rate 
INT DEFAULT NULL, UNIQUE INDEX UNIQ_31BE9FADFC51B88 (show_date_id), PRIMARY KEY(id)) 
DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE theater (id INT AUTO_INCREMENT NOT NULL, 
user_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, 
address1 VARCHAR(255) DEFAULT NULL, address2 VARCHAR(255) DEFAULT NULL, 
zip_code INT DEFAULT NULL, city VARCHAR(255) DEFAULT NULL, phone_number VARCHAR(255) DEFAULT NULL, 
go VARCHAR(255) DEFAULT NULL, website VARCHAR(255) DEFAULT NULL, 
base_rate DOUBLE PRECISION DEFAULT NULL, lat DOUBLE PRECISION DEFAULT NULL, 
longitude DOUBLE PRECISION DEFAULT NULL, UNIQUE INDEX UNIQ_46DD8154A76ED395 (user_id),
 PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE show_date (id INT AUTO_INCREMENT NOT NULL, 
show_id_id INT DEFAULT NULL, 
date_show DATETIME NOT NULL, INDEX IDX_7669E1B97DF5FA8B (show_id_id), PRIMARY KEY(id)) 
DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE spectacle ADD CONSTRAINT FK_E55076F44ECC313E 
FOREIGN KEY (theater_id_id) REFERENCES theater (id)');
        $this->addSql('ALTER TABLE show_rate ADD CONSTRAINT FK_31BE9FADFC51B88 
FOREIGN KEY (show_date_id) REFERENCES show_date (id)');
        $this->addSql('ALTER TABLE theater ADD CONSTRAINT FK_46DD8154A76ED395 
FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE show_date ADD CONSTRAINT FK_7669E1B97DF5FA8B 
FOREIGN KEY (show_id_id) REFERENCES spectacle (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()
                ->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE show_date DROP FOREIGN KEY FK_7669E1B97DF5FA8B');
        $this->addSql('ALTER TABLE theater DROP FOREIGN KEY FK_46DD8154A76ED395');
        $this->addSql('ALTER TABLE spectacle DROP FOREIGN KEY FK_E55076F44ECC313E');
        $this->addSql('ALTER TABLE show_rate DROP FOREIGN KEY FK_31BE9FADFC51B88');
        $this->addSql('DROP TABLE spectacle');
        $this->addSql('DROP TABLE param');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE show_rate');
        $this->addSql('DROP TABLE theater');
        $this->addSql('DROP TABLE show_date');
    }
}
