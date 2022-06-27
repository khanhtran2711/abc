<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220618032552 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE animal ADD CONSTRAINT FK_6AAB231FE6ADA943 FOREIGN KEY (cat_id) REFERENCES cat_ani (id)');
        $this->addSql('CREATE INDEX IDX_6AAB231FE6ADA943 ON animal (cat_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE animal DROP FOREIGN KEY FK_6AAB231FE6ADA943');
        $this->addSql('DROP INDEX IDX_6AAB231FE6ADA943 ON animal');
    }
}
