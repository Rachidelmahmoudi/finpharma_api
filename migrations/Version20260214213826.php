<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260214213826 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE establishment CHANGE type type ENUM(\'pharmacy\', \'doctor\', \'laboratory\', \'other\') NOT NULL');
        $this->addSql('ALTER TABLE open_pharmacy DROP INDEX UNIQ_F218CB748A94ABE2, ADD INDEX IDX_F218CB748A94ABE2 (pharmacy_id)');
        $this->addSql('ALTER TABLE open_pharmacy ADD open_at DATETIME DEFAULT NULL, ADD close_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE pharmacies DROP FOREIGN KEY FK_3AE5FA2AEDB939D');
        $this->addSql('DROP INDEX UNIQ_3AE5FA2AEDB939D ON pharmacies');
        $this->addSql('ALTER TABLE pharmacies ADD town VARCHAR(100) DEFAULT NULL, ADD email VARCHAR(50) DEFAULT NULL, DROP open_pharmacy_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE establishment CHANGE type type VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE open_pharmacy DROP INDEX IDX_F218CB748A94ABE2, ADD UNIQUE INDEX UNIQ_F218CB748A94ABE2 (pharmacy_id)');
        $this->addSql('ALTER TABLE open_pharmacy DROP open_at, DROP close_at');
        $this->addSql('ALTER TABLE pharmacies ADD open_pharmacy_id INT DEFAULT NULL, DROP town, DROP email');
        $this->addSql('ALTER TABLE pharmacies ADD CONSTRAINT FK_3AE5FA2AEDB939D FOREIGN KEY (open_pharmacy_id) REFERENCES open_pharmacy (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_3AE5FA2AEDB939D ON pharmacies (open_pharmacy_id)');
    }
}
