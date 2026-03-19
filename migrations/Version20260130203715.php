<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260130203715 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE analyse_category (id INT AUTO_INCREMENT NOT NULL, parent_category_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_762C0F83796A8F92 (parent_category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE analyse_to_do (id INT AUTO_INCREMENT NOT NULL, analyse_id INT DEFAULT NULL, consultation_id INT DEFAULT NULL, laboratory_id VARCHAR(255) DEFAULT NULL, has_result TINYINT(1) DEFAULT NULL, remarks LONGTEXT NOT NULL, result VARCHAR(255) DEFAULT NULL, price DOUBLE PRECISION DEFAULT NULL, INDEX IDX_C91177691EFE06BF (analyse_id), INDEX IDX_C911776962FF6CDF (consultation_id), INDEX IDX_C91177692F2A371E (laboratory_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE analyses (id INT AUTO_INCREMENT NOT NULL, category_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, price DOUBLE PRECISION DEFAULT NULL, b_index VARCHAR(10) DEFAULT NULL, INDEX IDX_AC86883C12469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE consultation (id INT AUTO_INCREMENT NOT NULL, doctor_id VARCHAR(255) DEFAULT NULL, medical_file_id INT DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', price DOUBLE PRECISION DEFAULT NULL, status INT DEFAULT NULL, type VARCHAR(10) DEFAULT NULL, INDEX IDX_964685A687F4FB17 (doctor_id), INDEX IDX_964685A6D5C999A2 (medical_file_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE doctor (id VARCHAR(255) NOT NULL, specialty_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, address LONGTEXT DEFAULT NULL, phone VARCHAR(20) DEFAULT NULL, city VARCHAR(30) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, latitude VARCHAR(40) DEFAULT NULL, longitude VARCHAR(40) DEFAULT NULL, INDEX IDX_1FC0F36A9A353316 (specialty_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE establishment (id INT AUTO_INCREMENT NOT NULL, type ENUM(\'pharmacy\', \'doctor\', \'laboratory\', \'other\') NOT NULL, target VARCHAR(255) DEFAULT NULL, custom_type VARCHAR(60) DEFAULT NULL, custom_address VARCHAR(255) DEFAULT NULL, custom_phone VARCHAR(20) DEFAULT NULL, custom_city VARCHAR(40) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE establishment_user (establishment_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_22EE7D5A8565851 (establishment_id), INDEX IDX_22EE7D5AA76ED395 (user_id), PRIMARY KEY(establishment_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE laboratory (id VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, address VARCHAR(255) DEFAULT NULL, city VARCHAR(30) DEFAULT NULL, phone VARCHAR(30) DEFAULT NULL, description LONGTEXT DEFAULT NULL, latitude VARCHAR(40) DEFAULT NULL, longitude VARCHAR(40) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE medical_file (id INT AUTO_INCREMENT NOT NULL, patient_id INT DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', name VARCHAR(255) DEFAULT NULL, insurance VARCHAR(30) DEFAULT NULL, total_price DOUBLE PRECISION DEFAULT NULL, status INT NOT NULL, INDEX IDX_DF6C9C386B899279 (patient_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE medication (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, presentation VARCHAR(100) DEFAULT NULL, dosage VARCHAR(100) DEFAULT NULL, composition VARCHAR(255) NOT NULL, price DOUBLE PRECISION NOT NULL, hospital_price DOUBLE PRECISION NOT NULL, indications LONGTEXT DEFAULT NULL, contraindications LONGTEXT DEFAULT NULL, status VARCHAR(1) NOT NULL, nature VARCHAR(1) NOT NULL, is_refundable TINYINT(1) NOT NULL, is_recalled TINYINT(1) NOT NULL, manufacturer VARCHAR(100) DEFAULT NULL, therapeutic_class VARCHAR(100) DEFAULT NULL, can_pregnancy TINYINT(1) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE open_pharmacy (id INT AUTO_INCREMENT NOT NULL, pharmacy_id VARCHAR(255) DEFAULT NULL, town VARCHAR(255) DEFAULT NULL, duty_type VARCHAR(30) DEFAULT NULL, garde_status VARCHAR(50) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_F218CB748A94ABE2 (pharmacy_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE patient (id INT AUTO_INCREMENT NOT NULL, first_name VARCHAR(100) NOT NULL, last_name VARCHAR(100) NOT NULL, cin VARCHAR(20) DEFAULT NULL, passport_id VARCHAR(100) DEFAULT NULL, birth_date DATE DEFAULT NULL, address VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pharmacies (id VARCHAR(255) NOT NULL, open_pharmacy_id INT DEFAULT NULL, name VARCHAR(150) NOT NULL, address VARCHAR(255) NOT NULL, phone VARCHAR(255) DEFAULT NULL, city VARCHAR(255) DEFAULT NULL, reference VARCHAR(255) DEFAULT NULL, category VARCHAR(255) DEFAULT NULL, latitude DOUBLE PRECISION DEFAULT NULL, longitude DOUBLE PRECISION DEFAULT NULL, google_maps_url VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_3AE5FA2AEDB939D (open_pharmacy_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE prescription (id INT AUTO_INCREMENT NOT NULL, medication_id INT DEFAULT NULL, consultation_id INT DEFAULT NULL, description LONGTEXT DEFAULT NULL, price DOUBLE PRECISION DEFAULT NULL, INDEX IDX_1FBFB8D92C4DE6DA (medication_id), INDEX IDX_1FBFB8D962FF6CDF (consultation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE refresh_tokens (id INT AUTO_INCREMENT NOT NULL, refresh_token VARCHAR(128) NOT NULL, username VARCHAR(255) NOT NULL, valid DATETIME NOT NULL, UNIQUE INDEX UNIQ_9BACE7E1C74F2195 (refresh_token), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reset_password_request (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', expires_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_7CE748AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE specialty (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, first_name VARCHAR(100) NOT NULL, last_name VARCHAR(100) NOT NULL, is_active TINYINT(1) DEFAULT NULL, is_verified TINYINT(1) NOT NULL, phone VARCHAR(20) DEFAULT NULL, google VARCHAR(255) DEFAULT NULL, linkedin VARCHAR(255) DEFAULT NULL, username VARCHAR(100) DEFAULT NULL, UNIQUE INDEX UNIQ_1483A5E9E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE analyse_category ADD CONSTRAINT FK_762C0F83796A8F92 FOREIGN KEY (parent_category_id) REFERENCES analyse_category (id)');
        $this->addSql('ALTER TABLE analyse_to_do ADD CONSTRAINT FK_C91177691EFE06BF FOREIGN KEY (analyse_id) REFERENCES analyses (id)');
        $this->addSql('ALTER TABLE analyse_to_do ADD CONSTRAINT FK_C911776962FF6CDF FOREIGN KEY (consultation_id) REFERENCES consultation (id)');
        $this->addSql('ALTER TABLE analyse_to_do ADD CONSTRAINT FK_C91177692F2A371E FOREIGN KEY (laboratory_id) REFERENCES laboratory (id)');
        $this->addSql('ALTER TABLE analyses ADD CONSTRAINT FK_AC86883C12469DE2 FOREIGN KEY (category_id) REFERENCES analyse_category (id)');
        $this->addSql('ALTER TABLE consultation ADD CONSTRAINT FK_964685A687F4FB17 FOREIGN KEY (doctor_id) REFERENCES doctor (id)');
        $this->addSql('ALTER TABLE consultation ADD CONSTRAINT FK_964685A6D5C999A2 FOREIGN KEY (medical_file_id) REFERENCES medical_file (id)');
        $this->addSql('ALTER TABLE doctor ADD CONSTRAINT FK_1FC0F36A9A353316 FOREIGN KEY (specialty_id) REFERENCES specialty (id)');
        $this->addSql('ALTER TABLE establishment_user ADD CONSTRAINT FK_22EE7D5A8565851 FOREIGN KEY (establishment_id) REFERENCES establishment (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE establishment_user ADD CONSTRAINT FK_22EE7D5AA76ED395 FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE medical_file ADD CONSTRAINT FK_DF6C9C386B899279 FOREIGN KEY (patient_id) REFERENCES patient (id)');
        $this->addSql('ALTER TABLE open_pharmacy ADD CONSTRAINT FK_F218CB748A94ABE2 FOREIGN KEY (pharmacy_id) REFERENCES pharmacies (id)');
        $this->addSql('ALTER TABLE pharmacies ADD CONSTRAINT FK_3AE5FA2AEDB939D FOREIGN KEY (open_pharmacy_id) REFERENCES open_pharmacy (id)');
        $this->addSql('ALTER TABLE prescription ADD CONSTRAINT FK_1FBFB8D92C4DE6DA FOREIGN KEY (medication_id) REFERENCES medication (id)');
        $this->addSql('ALTER TABLE prescription ADD CONSTRAINT FK_1FBFB8D962FF6CDF FOREIGN KEY (consultation_id) REFERENCES consultation (id)');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE analyse_category DROP FOREIGN KEY FK_762C0F83796A8F92');
        $this->addSql('ALTER TABLE analyse_to_do DROP FOREIGN KEY FK_C91177691EFE06BF');
        $this->addSql('ALTER TABLE analyse_to_do DROP FOREIGN KEY FK_C911776962FF6CDF');
        $this->addSql('ALTER TABLE analyse_to_do DROP FOREIGN KEY FK_C91177692F2A371E');
        $this->addSql('ALTER TABLE analyses DROP FOREIGN KEY FK_AC86883C12469DE2');
        $this->addSql('ALTER TABLE consultation DROP FOREIGN KEY FK_964685A687F4FB17');
        $this->addSql('ALTER TABLE consultation DROP FOREIGN KEY FK_964685A6D5C999A2');
        $this->addSql('ALTER TABLE doctor DROP FOREIGN KEY FK_1FC0F36A9A353316');
        $this->addSql('ALTER TABLE establishment_user DROP FOREIGN KEY FK_22EE7D5A8565851');
        $this->addSql('ALTER TABLE establishment_user DROP FOREIGN KEY FK_22EE7D5AA76ED395');
        $this->addSql('ALTER TABLE medical_file DROP FOREIGN KEY FK_DF6C9C386B899279');
        $this->addSql('ALTER TABLE open_pharmacy DROP FOREIGN KEY FK_F218CB748A94ABE2');
        $this->addSql('ALTER TABLE pharmacies DROP FOREIGN KEY FK_3AE5FA2AEDB939D');
        $this->addSql('ALTER TABLE prescription DROP FOREIGN KEY FK_1FBFB8D92C4DE6DA');
        $this->addSql('ALTER TABLE prescription DROP FOREIGN KEY FK_1FBFB8D962FF6CDF');
        $this->addSql('ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748AA76ED395');
        $this->addSql('DROP TABLE analyse_category');
        $this->addSql('DROP TABLE analyse_to_do');
        $this->addSql('DROP TABLE analyses');
        $this->addSql('DROP TABLE consultation');
        $this->addSql('DROP TABLE doctor');
        $this->addSql('DROP TABLE establishment');
        $this->addSql('DROP TABLE establishment_user');
        $this->addSql('DROP TABLE laboratory');
        $this->addSql('DROP TABLE medical_file');
        $this->addSql('DROP TABLE medication');
        $this->addSql('DROP TABLE open_pharmacy');
        $this->addSql('DROP TABLE patient');
        $this->addSql('DROP TABLE pharmacies');
        $this->addSql('DROP TABLE prescription');
        $this->addSql('DROP TABLE refresh_tokens');
        $this->addSql('DROP TABLE reset_password_request');
        $this->addSql('DROP TABLE specialty');
        $this->addSql('DROP TABLE users');
    }
}
