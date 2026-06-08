<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260608175745 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE establishment CHANGE type type ENUM(\'pharmacy\', \'doctor\', \'laboratory\', \'other\') NOT NULL');
        $this->addSql('ALTER TABLE medication ADD barcode VARCHAR(40) DEFAULT NULL');
        $this->addSql('ALTER TABLE users ADD status INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE establishment CHANGE type type VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE medication DROP barcode');
        $this->addSql('ALTER TABLE users DROP status');
    }
}
