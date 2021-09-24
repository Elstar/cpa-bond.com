<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210924212556 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE postback DROP INDEX IDX_79E255BEA76ED395, ADD UNIQUE INDEX UNIQ_79E255BEA76ED395 (user_id)');
        $this->addSql('ALTER TABLE stats ADD payoff DOUBLE PRECISION DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE postback DROP INDEX UNIQ_79E255BEA76ED395, ADD INDEX IDX_79E255BEA76ED395 (user_id)');
        $this->addSql('ALTER TABLE stats DROP payoff');
    }
}
