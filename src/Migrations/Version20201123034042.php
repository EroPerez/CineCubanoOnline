<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201123034042 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');


        $this->addSql('CREATE TABLE core_service_translation (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', translatable_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', locale VARCHAR(128) NOT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_72DCA5902C2AC5D3 (translatable_id), UNIQUE INDEX core_service_translation_unique_translation (translatable_id, locale), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE message (id INT AUTO_INCREMENT NOT NULL, thread_id INT NOT NULL, sender_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', body LONGTEXT NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_B6BD307FE2904019 (thread_id), INDEX IDX_B6BD307FF624B39D (sender_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE message_metadata (id INT AUTO_INCREMENT NOT NULL, message_id INT NOT NULL, participant_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', is_read TINYINT(1) NOT NULL, INDEX IDX_4632F005537A1329 (message_id), INDEX IDX_4632F0059D1C3019 (participant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE thread (id INT AUTO_INCREMENT NOT NULL, created_by_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', subject VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, is_spam TINYINT(1) NOT NULL, INDEX IDX_31204C83B03A8386 (created_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE thread_metadata (id INT AUTO_INCREMENT NOT NULL, thread_id INT NOT NULL, participant_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', is_deleted TINYINT(1) NOT NULL, last_participant_message_date DATETIME DEFAULT NULL, last_message_date DATETIME DEFAULT NULL, INDEX IDX_40A577C8E2904019 (thread_id), INDEX IDX_40A577C89D1C3019 (participant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE core_service_translation ADD CONSTRAINT FK_72DCA5902C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES core_service (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FE2904019 FOREIGN KEY (thread_id) REFERENCES thread (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FF624B39D FOREIGN KEY (sender_id) REFERENCES fos_user__user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE message_metadata ADD CONSTRAINT FK_4632F005537A1329 FOREIGN KEY (message_id) REFERENCES message (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE message_metadata ADD CONSTRAINT FK_4632F0059D1C3019 FOREIGN KEY (participant_id) REFERENCES fos_user__user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE thread ADD CONSTRAINT FK_31204C83B03A8386 FOREIGN KEY (created_by_id) REFERENCES fos_user__user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE thread_metadata ADD CONSTRAINT FK_40A577C8E2904019 FOREIGN KEY (thread_id) REFERENCES thread (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE thread_metadata ADD CONSTRAINT FK_40A577C89D1C3019 FOREIGN KEY (participant_id) REFERENCES fos_user__user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE core_service DROP name');
        $this->addSql('ALTER TABLE manage_company ADD video VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE message_metadata DROP FOREIGN KEY FK_4632F005537A1329');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FE2904019');
        $this->addSql('ALTER TABLE thread_metadata DROP FOREIGN KEY FK_40A577C8E2904019');        
        $this->addSql('DROP TABLE core_service_translation');
        $this->addSql('DROP TABLE message');
        $this->addSql('DROP TABLE message_metadata');
        $this->addSql('DROP TABLE thread');
        $this->addSql('DROP TABLE thread_metadata');
        $this->addSql('ALTER TABLE core_service ADD name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE manage_company DROP video');
    }
}
