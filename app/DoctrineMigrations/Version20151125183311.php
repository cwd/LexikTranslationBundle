<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20151125183311 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE MorticianUser DROP FOREIGN KEY FK_AF3AA0D7BF396750');
        $this->addSql('DROP INDEX UNIQ_AF3AA0D7BF396750 ON MorticianUser');
        $this->addSql('ALTER TABLE MorticianUser DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE MorticianUser CHANGE id userId INT UNSIGNED NOT NULL');
        $this->addSql('ALTER TABLE MorticianUser ADD CONSTRAINT FK_AF3AA0D764B64DCC FOREIGN KEY (userId) REFERENCES User (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_AF3AA0D764B64DCC ON MorticianUser (userId)');
        $this->addSql('ALTER TABLE MorticianUser ADD PRIMARY KEY (userId)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE MorticianUser DROP FOREIGN KEY FK_AF3AA0D764B64DCC');
        $this->addSql('DROP INDEX UNIQ_AF3AA0D764B64DCC ON MorticianUser');
        $this->addSql('ALTER TABLE MorticianUser DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE MorticianUser CHANGE userid id INT UNSIGNED NOT NULL');
        $this->addSql('ALTER TABLE MorticianUser ADD CONSTRAINT FK_AF3AA0D7BF396750 FOREIGN KEY (id) REFERENCES User (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_AF3AA0D7BF396750 ON MorticianUser (id)');
        $this->addSql('ALTER TABLE MorticianUser ADD PRIMARY KEY (id)');
        $this->addSql('ALTER TABLE Product DROP FOREIGN KEY FK_1CF73D31B9616544');
        $this->addSql('DROP INDEX IDX_1CF73D31B9616544 ON Product');
        $this->addSql('ALTER TABLE Product ADD mainImage_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE Product ADD CONSTRAINT FK_1CF73D3144D00FAF FOREIGN KEY (mainImage_id) REFERENCES Media (id)');
        $this->addSql('CREATE INDEX IDX_1CF73D3144D00FAF ON Product (mainImage_id)');
        $this->addSql('ALTER TABLE ProductCategory DROP FOREIGN KEY FK_D26EBFC410F3034D');
        $this->addSql('DROP INDEX IDX_D26EBFC410F3034D ON ProductCategory');
        $this->addSql('ALTER TABLE ProductCategory ADD image_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE ProductCategory ADD CONSTRAINT FK_D26EBFC43DA5256D FOREIGN KEY (image_id) REFERENCES Media (id)');
        $this->addSql('CREATE INDEX IDX_D26EBFC43DA5256D ON ProductCategory (image_id)');
    }
}
