<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250320000820 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE purchase_proof (id INT AUTO_INCREMENT NOT NULL, item_id INT NOT NULL, congrats_text VARCHAR(255) NOT NULL, image_path VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_2F32C3F7126F525E (item_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE purchase_proof ADD CONSTRAINT FK_2F32C3F7126F525E FOREIGN KEY (item_id) REFERENCES item (id)');
        $this->addSql('ALTER TABLE item ADD CONSTRAINT FK_1F1B251EFECA7547 FOREIGN KEY (purchase_proof_id) REFERENCES purchase_proof (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE item DROP FOREIGN KEY FK_1F1B251EFECA7547');
        $this->addSql('ALTER TABLE purchase_proof DROP FOREIGN KEY FK_2F32C3F7126F525E');
        $this->addSql('DROP TABLE purchase_proof');
    }
}
