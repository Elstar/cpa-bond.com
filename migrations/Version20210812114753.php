<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210812114753 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE stream ADD source_traffic INT NOT NULL, ADD postback_create VARCHAR(255) DEFAULT NULL, ADD postback_approve VARCHAR(255) DEFAULT NULL, ADD postback_decline VARCHAR(255) DEFAULT NULL, ADD postback_trash VARCHAR(255) DEFAULT NULL, ADD google_tag_id VARCHAR(255) DEFAULT NULL, ADD google_tag_conversion_id VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE stream DROP source_traffic, DROP postback_create, DROP postback_approve, DROP postback_decline, DROP postback_trash, DROP google_tag_id, DROP google_tag_conversion_id');
    }
}
