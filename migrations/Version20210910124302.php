<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210910124302 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE balance_operations ADD user_id INT UNSIGNED NOT NULL');
        $this->addSql('ALTER TABLE balance_operations ADD CONSTRAINT FK_70B7556AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_70B7556AA76ED395 ON balance_operations (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE balance_operations DROP FOREIGN KEY FK_70B7556AA76ED395');
        $this->addSql('DROP INDEX IDX_70B7556AA76ED395 ON balance_operations');
        $this->addSql('ALTER TABLE balance_operations DROP user_id');
    }
}
