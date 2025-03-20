<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250319235558 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE purchase_proof ADD item_id INT NOT NULL');
        $this->addSql('ALTER TABLE purchase_proof ADD CONSTRAINT FK_2F32C3F7126F525E FOREIGN KEY (item_id) REFERENCES item (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2F32C3F7126F525E ON purchase_proof (item_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE purchase_proof DROP FOREIGN KEY FK_2F32C3F7126F525E');
        $this->addSql('DROP INDEX UNIQ_2F32C3F7126F525E ON purchase_proof');
        $this->addSql('ALTER TABLE purchase_proof DROP item_id');
    }
}
