<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240202133153 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE orders_details DROP FOREIGN KEY FK_835379F1CFFE9AD6');
        $this->addSql('ALTER TABLE orders_details DROP FOREIGN KEY FK_835379F1D73DB560');
        $this->addSql('DROP INDEX IDX_835379F1CFFE9AD6 ON orders_details');
        $this->addSql('DROP INDEX IDX_835379F1D73DB560 ON orders_details');
        $this->addSql('ALTER TABLE orders_details ADD id INT AUTO_INCREMENT NOT NULL, DROP orders_id, DROP plat_id, DROP PRIMARY KEY, ADD PRIMARY KEY (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE orders_details MODIFY id INT NOT NULL');
        $this->addSql('DROP INDEX `PRIMARY` ON orders_details');
        $this->addSql('ALTER TABLE orders_details ADD orders_id INT NOT NULL, ADD plat_id INT NOT NULL, DROP id');
        $this->addSql('ALTER TABLE orders_details ADD CONSTRAINT FK_835379F1CFFE9AD6 FOREIGN KEY (orders_id) REFERENCES orders (id)');
        $this->addSql('ALTER TABLE orders_details ADD CONSTRAINT FK_835379F1D73DB560 FOREIGN KEY (plat_id) REFERENCES plat (id)');
        $this->addSql('CREATE INDEX IDX_835379F1CFFE9AD6 ON orders_details (orders_id)');
        $this->addSql('CREATE INDEX IDX_835379F1D73DB560 ON orders_details (plat_id)');
        $this->addSql('ALTER TABLE orders_details ADD PRIMARY KEY (orders_id, plat_id)');
    }
}
