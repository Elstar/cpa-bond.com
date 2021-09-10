<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210910082756 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE payout (id INT AUTO_INCREMENT NOT NULL, user_id INT UNSIGNED NOT NULL, payment_system_id INT NOT NULL, sum INT NOT NULL, INDEX IDX_4E2EA902A76ED395 (user_id), INDEX IDX_4E2EA90293BC008D (payment_system_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE payout ADD CONSTRAINT FK_4E2EA902A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE payout ADD CONSTRAINT FK_4E2EA90293BC008D FOREIGN KEY (payment_system_id) REFERENCES payment_system (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE payout');
    }
}
