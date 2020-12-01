<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201121232459 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');


        $this->addSql('ALTER TABLE company_service DROP FOREIGN KEY FK_C1CF8005979B1AD6');
        $this->addSql('CREATE TABLE manage_company (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', owner_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', name VARCHAR(100) NOT NULL, legal_poeple VARCHAR(100) NOT NULL, jurist_people VARCHAR(100) NOT NULL, inscription_number VARCHAR(20) NOT NULL, address VARCHAR(100) NOT NULL, phone VARCHAR(20) NOT NULL, logo VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, province_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', INDEX IDX_12DA9F39E946114A (province_id), INDEX IDX_12DA9F397E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE manage_company_services (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', company_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', service_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', description LONGTEXT NOT NULL, logo VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_686D8B46979B1AD6 (company_id), INDEX IDX_686D8B46ED5CA9E6 (service_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE manage_company ADD CONSTRAINT FK_12DA9F397E3C61F9 FOREIGN KEY (owner_id) REFERENCES fos_user__user (id)');
        $this->addSql('ALTER TABLE manage_company_services ADD CONSTRAINT FK_686D8B46979B1AD6 FOREIGN KEY (company_id) REFERENCES manage_company (id)');
        $this->addSql('ALTER TABLE manage_company_services ADD CONSTRAINT FK_686D8B46ED5CA9E6 FOREIGN KEY (service_id) REFERENCES core_service (id)');
        $this->addSql('DROP TABLE company');
        $this->addSql('DROP TABLE company_service');  
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE manage_company_services DROP FOREIGN KEY FK_686D8B46979B1AD6');        
        $this->addSql('CREATE TABLE company (id CHAR(36) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:uuid)\', owner_id CHAR(36) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:uuid)\', name VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, legal_poeple VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, jurist_people VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, inscription_number VARCHAR(20) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, address VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, phone VARCHAR(20) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, province_id CHAR(36) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:guid)\', logo VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_4FBF094FE946114A (province_id), INDEX IDX_4FBF094F7E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE company_service (company_id CHAR(36) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:uuid)\', service_id CHAR(36) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:uuid)\', INDEX IDX_C1CF8005ED5CA9E6 (service_id), INDEX IDX_C1CF8005979B1AD6 (company_id), PRIMARY KEY(company_id, service_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        
        $this->addSql('ALTER TABLE company ADD CONSTRAINT FK_4FBF094F7E3C61F9 FOREIGN KEY (owner_id) REFERENCES fos_user__user (id)');
        $this->addSql('ALTER TABLE company_service ADD CONSTRAINT FK_C1CF8005979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE company_service ADD CONSTRAINT FK_C1CF8005ED5CA9E6 FOREIGN KEY (service_id) REFERENCES core_service (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE manage_company');
        $this->addSql('DROP TABLE manage_company_services');
    }
}
