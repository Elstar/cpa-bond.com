<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210910092133 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE payout ADD reason VARCHAR(255) DEFAULT NULL COMMENT \'reason of reject\', CHANGE status status INT UNSIGNED DEFAULT 0 NOT NULL COMMENT \'0 - new, 1 - payed, 2 - reject\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE payout DROP reason, CHANGE status status INT UNSIGNED DEFAULT 0 NOT NULL');
    }
}
