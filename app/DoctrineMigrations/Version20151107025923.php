<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20151107025923 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE ObituaryMedia (id INT AUTO_INCREMENT NOT NULL, type INT NOT NULL, mediaId INT NOT NULL, obituaryId INT NOT NULL, INDEX IDX_19BBBF7527D9F5AC (mediaId), INDEX IDX_19BBBF7568BF794B (obituaryId), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ObituaryMedia ADD CONSTRAINT FK_19BBBF7527D9F5AC FOREIGN KEY (mediaId) REFERENCES Media (id)');
        $this->addSql('ALTER TABLE ObituaryMedia ADD CONSTRAINT FK_19BBBF7568BF794B FOREIGN KEY (obituaryId) REFERENCES Obituary (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE ObituaryMedia');
    }
}
