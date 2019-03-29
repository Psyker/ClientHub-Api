<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190329163334 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE business_segment (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE client ADD business_segment_id INT DEFAULT NULL, ADD description LONGTEXT NOT NULL, ADD city VARCHAR(200) NOT NULL, ADD phone VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE client ADD CONSTRAINT FK_C74404551B8A6206 FOREIGN KEY (business_segment_id) REFERENCES business_segment (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C74404551B8A6206 ON client (business_segment_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE client DROP FOREIGN KEY FK_C74404551B8A6206');
        $this->addSql('DROP TABLE business_segment');
        $this->addSql('DROP INDEX UNIQ_C74404551B8A6206 ON client');
        $this->addSql('ALTER TABLE client DROP business_segment_id, DROP description, DROP city, DROP phone');
    }
}
