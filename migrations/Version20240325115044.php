<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240325115044 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE orders_details MODIFY id INT NOT NULL');
        $this->addSql('DROP INDEX `primary` ON orders_details');
        $this->addSql('ALTER TABLE orders_details DROP id, CHANGE orders_id orders_id INT NOT NULL, CHANGE plat_id plat_id INT NOT NULL');
        $this->addSql('ALTER TABLE orders_details ADD PRIMARY KEY (orders_id, plat_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE orders_details ADD id INT AUTO_INCREMENT NOT NULL, CHANGE orders_id orders_id INT DEFAULT NULL, CHANGE plat_id plat_id INT DEFAULT NULL, DROP PRIMARY KEY, ADD PRIMARY KEY (id)');
    }
}
