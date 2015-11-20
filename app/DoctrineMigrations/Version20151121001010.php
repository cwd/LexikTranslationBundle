<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20151121001010 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Product DROP FOREIGN KEY FK_1CF73D3144D00FAF');
        $this->addSql('DROP INDEX IDX_1CF73D3144D00FAF ON Product');
        $this->addSql('ALTER TABLE Product DROP mainImage_id');
        $this->addSql('ALTER TABLE Product ADD CONSTRAINT FK_1CF73D31B9616544 FOREIGN KEY (mainImageId) REFERENCES Media (id)');
        $this->addSql('CREATE INDEX IDX_1CF73D31B9616544 ON Product (mainImageId)');
        $this->addSql('ALTER TABLE Customer ADD forumId INT DEFAULT NULL');
        $this->addSql('ALTER TABLE ProductCategory DROP FOREIGN KEY FK_D26EBFC43DA5256D');
        $this->addSql('DROP INDEX IDX_D26EBFC43DA5256D ON ProductCategory');
        $this->addSql('ALTER TABLE ProductCategory DROP image_id');
        $this->addSql('ALTER TABLE ProductCategory ADD CONSTRAINT FK_D26EBFC410F3034D FOREIGN KEY (imageId) REFERENCES Media (id)');
        $this->addSql('CREATE INDEX IDX_D26EBFC410F3034D ON ProductCategory (imageId)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Customer DROP forumId');
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
