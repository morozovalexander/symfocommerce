<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160316005111 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE favourites (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, product_id INT DEFAULT NULL, date DATETIME NOT NULL, INDEX IDX_7F07C501A76ED395 (user_id), INDEX IDX_7F07C5014584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE favourites ADD CONSTRAINT FK_7F07C501A76ED395 FOREIGN KEY (user_id) REFERENCES app_user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE favourites ADD CONSTRAINT FK_7F07C5014584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD12469DE2');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04ADA23B42D');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD12469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADA23B42D FOREIGN KEY (manufacturer_id) REFERENCES manufacturer (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE orders ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEEA76ED395 FOREIGN KEY (user_id) REFERENCES app_user (id)');
        $this->addSql('CREATE INDEX IDX_E52FFDEEA76ED395 ON orders (user_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE favourites');
        $this->addSql('ALTER TABLE orders DROP FOREIGN KEY FK_E52FFDEEA76ED395');
        $this->addSql('DROP INDEX IDX_E52FFDEEA76ED395 ON orders');
        $this->addSql('ALTER TABLE orders DROP user_id');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD12469DE2');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04ADA23B42D');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD12469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADA23B42D FOREIGN KEY (manufacturer_id) REFERENCES manufacturer (id)');
    }
}
