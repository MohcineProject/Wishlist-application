<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250319235641 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE item (id INT AUTO_INCREMENT NOT NULL, purchase_proof_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, url VARCHAR(255) NOT NULL, image VARCHAR(255) DEFAULT NULL, price DOUBLE PRECISION NOT NULL, UNIQUE INDEX UNIQ_1F1B251EFECA7547 (purchase_proof_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE item ADD CONSTRAINT FK_1F1B251EFECA7547 FOREIGN KEY (purchase_proof_id) REFERENCES purchase_proof (id)');
        $this->addSql('ALTER TABLE purchase_proof ADD item_id INT NOT NULL');
        $this->addSql('ALTER TABLE purchase_proof ADD CONSTRAINT FK_2F32C3F7126F525E FOREIGN KEY (item_id) REFERENCES item (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2F32C3F7126F525E ON purchase_proof (item_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE purchase_proof DROP FOREIGN KEY FK_2F32C3F7126F525E');
        $this->addSql('ALTER TABLE item DROP FOREIGN KEY FK_1F1B251EFECA7547');
        $this->addSql('DROP TABLE item');
        $this->addSql('DROP INDEX UNIQ_2F32C3F7126F525E ON purchase_proof');
        $this->addSql('ALTER TABLE purchase_proof DROP item_id');
    }
}
