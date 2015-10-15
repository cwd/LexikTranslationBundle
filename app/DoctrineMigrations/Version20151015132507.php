<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20151015132507 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE UserHasPermission (userId INT UNSIGNED NOT NULL, permissionId INT NOT NULL, INDEX IDX_8467C58C64B64DCC (userId), INDEX IDX_8467C58C605405B0 (permissionId), PRIMARY KEY(userId, permissionId)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Permission (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(150) NOT NULL, title VARCHAR(150) NOT NULL, description LONGTEXT DEFAULT NULL, entity VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_AF14917A5E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE UserHasPermission ADD CONSTRAINT FK_8467C58C64B64DCC FOREIGN KEY (userId) REFERENCES User (id)');
        $this->addSql('ALTER TABLE UserHasPermission ADD CONSTRAINT FK_8467C58C605405B0 FOREIGN KEY (permissionId) REFERENCES Permission (id)');
    }

    /**
     * @param Schema $schema
     */
    public function postUp(Schema $schema)
    {
        $this->addSql("INSERT INTO Permission (name, title, entity) VALUES
          ('mortician.view', 'View mortician', 'mortician'),
          ('mortician.edit', 'Edit mortician', 'mortician'),
          ('mortician.user.view', 'View users', 'mortician'),
          ('mortician.user.create', 'Create users', 'mortician'),
          ('mortician.user.edit',   'Edit users', 'mortician'),
          ('mortician.user.delete', 'Delete users', 'mortician'),
          ('mortician.branch.view', 'View branch', 'mortician'),
          ('mortician.branch.create', 'Create branch', 'mortician'),
          ('mortician.branch.edit', 'Edit branch', 'mortician'),
          ('mortician.branch.delete', 'Delete branch', 'mortician'),
          ('mortician.supplier.view', 'View suppliers', 'mortician'),
          ('mortician.supplier.add', 'Add suppliers', 'mortician'),
          ('mortician.supplier.remove', 'Remove suppliers', 'mortician'),
          ('mortician.supplier.propose', 'Propose supplier', 'mortician')
        ");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE UserHasPermission DROP FOREIGN KEY FK_8467C58C605405B0');
        $this->addSql('DROP TABLE UserHasPermission');
        $this->addSql('DROP TABLE Permission');
    }
}
