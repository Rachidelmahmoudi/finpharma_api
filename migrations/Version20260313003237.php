<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260313003237 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE establishment CHANGE type type ENUM(\'pharmacy\', \'doctor\', \'laboratory\', \'other\') NOT NULL');
        $this->addSql('ALTER TABLE open_pharmacy ADD am_from TIME NOT NULL, ADD pm_to TIME NOT NULL, ADD am_to TIME DEFAULT NULL, ADD pm_from TIME DEFAULT NULL, DROP open_at, DROP close_at');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE establishment CHANGE type type VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE open_pharmacy ADD open_at DATETIME DEFAULT NULL, ADD close_at DATETIME DEFAULT NULL, DROP am_from, DROP pm_to, DROP am_to, DROP pm_from');
    }
}
