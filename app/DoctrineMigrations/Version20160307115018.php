<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160307115018 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE product ADD date_created DATETIME NOT NULL, ADD date_updated DATETIME NOT NULL');
        $this->addSql('ALTER TABLE manufacturer ADD date_created DATETIME NOT NULL, ADD date_updated DATETIME NOT NULL');
        $this->addSql('ALTER TABLE category ADD date_created DATETIME NOT NULL, ADD date_updated DATETIME NOT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE category DROP date_created, DROP date_updated');
        $this->addSql('ALTER TABLE manufacturer DROP date_created, DROP date_updated');
        $this->addSql('ALTER TABLE product DROP date_created, DROP date_updated');
    }
}
