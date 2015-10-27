<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20151027031629 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE SupplierMedia (id INT AUTO_INCREMENT NOT NULL, description LONGTEXT DEFAULT NULL, supplierId INT NOT NULL, mediaId INT NOT NULL, INDEX IDX_88EE430FDF05A1D3 (supplierId), INDEX IDX_88EE430F27D9F5AC (mediaId), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE SupplierMedia ADD CONSTRAINT FK_88EE430FDF05A1D3 FOREIGN KEY (supplierId) REFERENCES Supplier (id)');
        $this->addSql('ALTER TABLE SupplierMedia ADD CONSTRAINT FK_88EE430F27D9F5AC FOREIGN KEY (mediaId) REFERENCES Media (id)');
        $this->addSql('DROP TABLE SupplierHasMedia');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE SupplierHasMedia (supplierId INT NOT NULL, mediaId INT NOT NULL, INDEX IDX_B0F0A905DF05A1D3 (supplierId), INDEX IDX_B0F0A90527D9F5AC (mediaId), PRIMARY KEY(supplierId, mediaId)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE SupplierHasMedia ADD CONSTRAINT FK_B0F0A90527D9F5AC FOREIGN KEY (mediaId) REFERENCES Media (id)');
        $this->addSql('ALTER TABLE SupplierHasMedia ADD CONSTRAINT FK_B0F0A905DF05A1D3 FOREIGN KEY (supplierId) REFERENCES Supplier (id)');
        $this->addSql('DROP TABLE SupplierMedia');
    }
}
