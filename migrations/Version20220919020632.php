<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220919020632 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE address CHANGE company compagny VARCHAR(255) DEFAULT NULL, CHANGE country contry VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE `order` DROP is_paid, CHANGE created_at created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE order_details CHANGE my_order_id my_order_id INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE address CHANGE compagny company VARCHAR(255) DEFAULT NULL, CHANGE contry country VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE order_details CHANGE my_order_id my_order_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE `order` ADD is_paid TINYINT(1) NOT NULL, CHANGE created_at created_at DATETIME NOT NULL');
    }
}
