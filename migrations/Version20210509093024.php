<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210509093024 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE campervan (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE equipment (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE equipment_availability (id INT AUTO_INCREMENT NOT NULL, station_id INT NOT NULL, equipment_id INT NOT NULL, booking_date DATE NOT NULL, booking_amount INT NOT NULL, INDEX IDX_9ADC552021BDB235 (station_id), INDEX IDX_9ADC5520517FE9FE (equipment_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rental_order (id INT AUTO_INCREMENT NOT NULL, campervan_id INT NOT NULL, start_station_id INT NOT NULL, end_station_id INT NOT NULL, start_date DATE NOT NULL, end_date DATE NOT NULL, INDEX IDX_6EC21D77B9D53E94 (campervan_id), INDEX IDX_6EC21D7753721DCB (start_station_id), INDEX IDX_6EC21D772FF5EABB (end_station_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rental_order_equipment (id INT AUTO_INCREMENT NOT NULL, rental_order_id INT NOT NULL, equipment_id INT NOT NULL, amount INT NOT NULL, INDEX IDX_34B438ABBDF9740B (rental_order_id), INDEX IDX_34B438AB517FE9FE (equipment_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE station (id INT AUTO_INCREMENT NOT NULL, location VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE station_equipment (id INT AUTO_INCREMENT NOT NULL, station_id INT NOT NULL, equipment_id INT NOT NULL, amount INT NOT NULL, INDEX IDX_51BCBB9821BDB235 (station_id), INDEX IDX_51BCBB98517FE9FE (equipment_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE equipment_availability ADD CONSTRAINT FK_9ADC552021BDB235 FOREIGN KEY (station_id) REFERENCES station (id)');
        $this->addSql('ALTER TABLE equipment_availability ADD CONSTRAINT FK_9ADC5520517FE9FE FOREIGN KEY (equipment_id) REFERENCES equipment (id)');
        $this->addSql('ALTER TABLE rental_order ADD CONSTRAINT FK_6EC21D77B9D53E94 FOREIGN KEY (campervan_id) REFERENCES campervan (id)');
        $this->addSql('ALTER TABLE rental_order ADD CONSTRAINT FK_6EC21D7753721DCB FOREIGN KEY (start_station_id) REFERENCES station (id)');
        $this->addSql('ALTER TABLE rental_order ADD CONSTRAINT FK_6EC21D772FF5EABB FOREIGN KEY (end_station_id) REFERENCES station (id)');
        $this->addSql('ALTER TABLE rental_order_equipment ADD CONSTRAINT FK_34B438ABBDF9740B FOREIGN KEY (rental_order_id) REFERENCES rental_order (id)');
        $this->addSql('ALTER TABLE rental_order_equipment ADD CONSTRAINT FK_34B438AB517FE9FE FOREIGN KEY (equipment_id) REFERENCES equipment (id)');
        $this->addSql('ALTER TABLE station_equipment ADD CONSTRAINT FK_51BCBB9821BDB235 FOREIGN KEY (station_id) REFERENCES station (id)');
        $this->addSql('ALTER TABLE station_equipment ADD CONSTRAINT FK_51BCBB98517FE9FE FOREIGN KEY (equipment_id) REFERENCES equipment (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE rental_order DROP FOREIGN KEY FK_6EC21D77B9D53E94');
        $this->addSql('ALTER TABLE equipment_availability DROP FOREIGN KEY FK_9ADC5520517FE9FE');
        $this->addSql('ALTER TABLE rental_order_equipment DROP FOREIGN KEY FK_34B438AB517FE9FE');
        $this->addSql('ALTER TABLE station_equipment DROP FOREIGN KEY FK_51BCBB98517FE9FE');
        $this->addSql('ALTER TABLE rental_order_equipment DROP FOREIGN KEY FK_34B438ABBDF9740B');
        $this->addSql('ALTER TABLE equipment_availability DROP FOREIGN KEY FK_9ADC552021BDB235');
        $this->addSql('ALTER TABLE rental_order DROP FOREIGN KEY FK_6EC21D7753721DCB');
        $this->addSql('ALTER TABLE rental_order DROP FOREIGN KEY FK_6EC21D772FF5EABB');
        $this->addSql('ALTER TABLE station_equipment DROP FOREIGN KEY FK_51BCBB9821BDB235');
        $this->addSql('DROP TABLE campervan');
        $this->addSql('DROP TABLE equipment');
        $this->addSql('DROP TABLE equipment_availability');
        $this->addSql('DROP TABLE rental_order');
        $this->addSql('DROP TABLE rental_order_equipment');
        $this->addSql('DROP TABLE station');
        $this->addSql('DROP TABLE station_equipment');
    }
}
