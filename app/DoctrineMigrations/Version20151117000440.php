<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20151117000440 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE ReminderHistory (id INT AUTO_INCREMENT NOT NULL, result VARCHAR(255) NOT NULL, reminderId INT NOT NULL, INDEX IDX_52D286223CA710C1 (reminderId), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Reminder (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(255) NOT NULL, deletedAt DATETIME DEFAULT NULL, state VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, updatedAt DATETIME DEFAULT NULL, createdAt DATETIME DEFAULT NULL, obituaryId INT NOT NULL, createdBy INT UNSIGNED DEFAULT NULL, updatedBy INT UNSIGNED DEFAULT NULL, INDEX IDX_B9412D1668BF794B (obituaryId), INDEX IDX_B9412D16D3564642 (createdBy), INDEX IDX_B9412D16E8DE7170 (updatedBy), INDEX IDX_REMINDER_TYPE_STATE (type, state), UNIQUE INDEX UQ_EMAIL_OBITUARY (email, obituaryId), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ReminderHistory ADD CONSTRAINT FK_52D286223CA710C1 FOREIGN KEY (reminderId) REFERENCES Reminder (id)');
        $this->addSql('ALTER TABLE Reminder ADD CONSTRAINT FK_B9412D1668BF794B FOREIGN KEY (obituaryId) REFERENCES Obituary (id)');
        $this->addSql('ALTER TABLE Reminder ADD CONSTRAINT FK_B9412D16D3564642 FOREIGN KEY (createdBy) REFERENCES User (id)');
        $this->addSql('ALTER TABLE Reminder ADD CONSTRAINT FK_B9412D16E8DE7170 FOREIGN KEY (updatedBy) REFERENCES User (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE ReminderHistory DROP FOREIGN KEY FK_52D286223CA710C1');
        $this->addSql('DROP TABLE ReminderHistory');
        $this->addSql('DROP TABLE Reminder');
    }
}
