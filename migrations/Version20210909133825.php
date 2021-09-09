<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210909133825 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE payment_system (id INT AUTO_INCREMENT NOT NULL, user_id INT UNSIGNED NOT NULL, payout_method_id INT NOT NULL, details LONGTEXT DEFAULT NULL COMMENT \'serrialize data of payment_detail fiels data\', INDEX IDX_EC758A1DA76ED395 (user_id), INDEX IDX_EC758A1DE02E02B0 (payout_method_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE payment_system ADD CONSTRAINT FK_EC758A1DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE payment_system ADD CONSTRAINT FK_EC758A1DE02E02B0 FOREIGN KEY (payout_method_id) REFERENCES pay_out_methods (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE payment_system');
    }
}
