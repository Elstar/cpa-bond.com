<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210817162957 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE stream (id INT UNSIGNED AUTO_INCREMENT NOT NULL, user_id INT UNSIGNED NOT NULL, offer_id INT UNSIGNED NOT NULL, pre_landing_id INT UNSIGNED DEFAULT NULL, landing_id INT UNSIGNED DEFAULT NULL, pre_landing_page_id INT UNSIGNED DEFAULT NULL, geo_id INT UNSIGNED NOT NULL, pay_type_id INT UNSIGNED NOT NULL, name VARCHAR(255) NOT NULL, url VARCHAR(255) NOT NULL, unique_id VARCHAR(13) NOT NULL, source_traffic INT NOT NULL, postback_create VARCHAR(255) DEFAULT NULL, postback_approve VARCHAR(255) DEFAULT NULL, postback_decline VARCHAR(255) DEFAULT NULL, postback_trash VARCHAR(255) DEFAULT NULL, google_tag_id VARCHAR(255) DEFAULT NULL, google_tag_conversion_id VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_F0E9BE1CA76ED395 (user_id), INDEX IDX_F0E9BE1C53C674EE (offer_id), INDEX IDX_F0E9BE1CB698DEF7 (pre_landing_id), INDEX IDX_F0E9BE1CEFD98736 (landing_id), INDEX IDX_F0E9BE1CA743AD6C (pre_landing_page_id), INDEX IDX_F0E9BE1CFA49D0B (geo_id), INDEX IDX_F0E9BE1C23A64B58 (pay_type_id), UNIQUE INDEX unique_id (unique_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE stream ADD CONSTRAINT FK_F0E9BE1CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE stream ADD CONSTRAINT FK_F0E9BE1C53C674EE FOREIGN KEY (offer_id) REFERENCES offer (id)');
        $this->addSql('ALTER TABLE stream ADD CONSTRAINT FK_F0E9BE1CB698DEF7 FOREIGN KEY (pre_landing_id) REFERENCES pre_landing (id)');
        $this->addSql('ALTER TABLE stream ADD CONSTRAINT FK_F0E9BE1CEFD98736 FOREIGN KEY (landing_id) REFERENCES landing (id)');
        $this->addSql('ALTER TABLE stream ADD CONSTRAINT FK_F0E9BE1CA743AD6C FOREIGN KEY (pre_landing_page_id) REFERENCES pre_landing_page (id)');
        $this->addSql('ALTER TABLE stream ADD CONSTRAINT FK_F0E9BE1CFA49D0B FOREIGN KEY (geo_id) REFERENCES geo (id)');
        $this->addSql('ALTER TABLE stream ADD CONSTRAINT FK_F0E9BE1C23A64B58 FOREIGN KEY (pay_type_id) REFERENCES pay_types (id)');
        $this->addSql('DROP TABLE uniqueId');
        $this->addSql('ALTER TABLE day_stats ADD CONSTRAINT FK_EE8ADB4ED0ED463E FOREIGN KEY (stream_id) REFERENCES stream (id)');
        $this->addSql('ALTER TABLE lead ADD CONSTRAINT FK_289161CBD0ED463E FOREIGN KEY (stream_id) REFERENCES stream (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE day_stats DROP FOREIGN KEY FK_EE8ADB4ED0ED463E');
        $this->addSql('ALTER TABLE lead DROP FOREIGN KEY FK_289161CBD0ED463E');
        $this->addSql('CREATE TABLE uniqueId (id INT UNSIGNED AUTO_INCREMENT NOT NULL, user_id INT UNSIGNED NOT NULL, offer_id INT UNSIGNED NOT NULL, pre_landing_id INT UNSIGNED DEFAULT NULL, landing_id INT UNSIGNED DEFAULT NULL, pre_landing_page_id INT UNSIGNED DEFAULT NULL, geo_id INT UNSIGNED NOT NULL, pay_type_id INT UNSIGNED NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, url VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, unique_id VARCHAR(13) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, source_traffic INT NOT NULL, postback_create VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, postback_approve VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, postback_decline VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, postback_trash VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, google_tag_id VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, google_tag_conversion_id VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_C8CBE8A0A76ED395 (user_id), INDEX IDX_C8CBE8A0B698DEF7 (pre_landing_id), INDEX IDX_C8CBE8A0A743AD6C (pre_landing_page_id), INDEX IDX_C8CBE8A023A64B58 (pay_type_id), INDEX IDX_C8CBE8A053C674EE (offer_id), INDEX IDX_C8CBE8A0EFD98736 (landing_id), INDEX IDX_C8CBE8A0FA49D0B (geo_id), UNIQUE INDEX unique_id (unique_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE uniqueId ADD CONSTRAINT FK_C8CBE8A023A64B58 FOREIGN KEY (pay_type_id) REFERENCES pay_types (id)');
        $this->addSql('ALTER TABLE uniqueId ADD CONSTRAINT FK_C8CBE8A053C674EE FOREIGN KEY (offer_id) REFERENCES offer (id)');
        $this->addSql('ALTER TABLE uniqueId ADD CONSTRAINT FK_C8CBE8A0A743AD6C FOREIGN KEY (pre_landing_page_id) REFERENCES pre_landing_page (id)');
        $this->addSql('ALTER TABLE uniqueId ADD CONSTRAINT FK_C8CBE8A0A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE uniqueId ADD CONSTRAINT FK_C8CBE8A0B698DEF7 FOREIGN KEY (pre_landing_id) REFERENCES pre_landing (id)');
        $this->addSql('ALTER TABLE uniqueId ADD CONSTRAINT FK_C8CBE8A0EFD98736 FOREIGN KEY (landing_id) REFERENCES landing (id)');
        $this->addSql('ALTER TABLE uniqueId ADD CONSTRAINT FK_C8CBE8A0FA49D0B FOREIGN KEY (geo_id) REFERENCES geo (id)');
        $this->addSql('DROP TABLE stream');
    }
}
