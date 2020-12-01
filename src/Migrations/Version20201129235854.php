<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201129235854 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE manage_company_services DROP FOREIGN KEY FK_686D8B46ED5CA9E6');
        $this->addSql('DROP INDEX IDX_686D8B46ED5CA9E6 ON manage_company_services');
        $this->addSql('ALTER TABLE manage_company_services ADD title VARCHAR(255) NOT NULL, CHANGE service_id category_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE manage_company_services ADD CONSTRAINT FK_686D8B4612469DE2 FOREIGN KEY (category_id) REFERENCES core_service (id)');
        $this->addSql('CREATE INDEX IDX_686D8B4612469DE2 ON manage_company_services (category_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE manage_company_services DROP FOREIGN KEY FK_686D8B4612469DE2');
        $this->addSql('DROP INDEX IDX_686D8B4612469DE2 ON manage_company_services');
        $this->addSql('ALTER TABLE manage_company_services DROP title, CHANGE category_id service_id CHAR(36) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE manage_company_services ADD CONSTRAINT FK_686D8B46ED5CA9E6 FOREIGN KEY (service_id) REFERENCES core_service (id)');
        $this->addSql('CREATE INDEX IDX_686D8B46ED5CA9E6 ON manage_company_services (service_id)');
    }
}
