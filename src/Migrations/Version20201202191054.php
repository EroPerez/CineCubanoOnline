<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201202191054 extends AbstractMigration {

    public function getDescription(): string {
        return '';
    }

    public function up(Schema $schema): void {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');


        $this->addSql('CREATE TABLE contact_conversation (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contact_conversation_user (contact_conversation_id INT NOT NULL, user_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', INDEX IDX_AD4F180292BB3CA8 (contact_conversation_id), INDEX IDX_AD4F1802A76ED395 (user_id), PRIMARY KEY(contact_conversation_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contact_message (id INT AUTO_INCREMENT NOT NULL, user_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', conversation_id INT NOT NULL, message LONGTEXT NOT NULL, seen TINYINT(1) NOT NULL, date DATETIME NOT NULL, INDEX IDX_2C9211FEA76ED395 (user_id), INDEX IDX_2C9211FE9AC0396 (conversation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE contact_conversation_user ADD CONSTRAINT FK_AD4F180292BB3CA8 FOREIGN KEY (contact_conversation_id) REFERENCES contact_conversation (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE contact_conversation_user ADD CONSTRAINT FK_AD4F1802A76ED395 FOREIGN KEY (user_id) REFERENCES fos_user__user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE contact_message ADD CONSTRAINT FK_2C9211FEA76ED395 FOREIGN KEY (user_id) REFERENCES fos_user__user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE contact_message ADD CONSTRAINT FK_2C9211FE9AC0396 FOREIGN KEY (conversation_id) REFERENCES contact_conversation (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE contact_conversation_user DROP FOREIGN KEY FK_AD4F180292BB3CA8');
        $this->addSql('ALTER TABLE contact_message DROP FOREIGN KEY FK_2C9211FE9AC0396');
        $this->addSql('DROP TABLE contact_conversation');
        $this->addSql('DROP TABLE contact_conversation_user');
        $this->addSql('DROP TABLE contact_message');
    }

}
