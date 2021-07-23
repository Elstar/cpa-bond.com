<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210723094558 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category (id INT UNSIGNED AUTO_INCREMENT NOT NULL, parent_id INT UNSIGNED DEFAULT 0 NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE currency (id INT UNSIGNED AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, sign VARCHAR(2) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE geo (id INT UNSIGNED AUTO_INCREMENT NOT NULL, name VARCHAR(3) NOT NULL, flag LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE landing (id INT UNSIGNED AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, url VARCHAR(255) NOT NULL, cr INT UNSIGNED DEFAULT 0, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE lead (id INT AUTO_INCREMENT NOT NULL, stream_id INT UNSIGNED NOT NULL, geo_id INT UNSIGNED NOT NULL, offer_id INT UNSIGNED NOT NULL, first_name VARCHAR(255) DEFAULT NULL, phone VARCHAR(255) NOT NULL, ip VARBINARY(16) NOT NULL COMMENT \'(DC2Type:ip)\', ua LONGTEXT NOT NULL, hash VARCHAR(32) NOT NULL, status INT UNSIGNED DEFAULT 0 NOT NULL, reject_type INT DEFAULT NULL, status_comment VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_289161CBD0ED463E (stream_id), INDEX IDX_289161CBFA49D0B (geo_id), INDEX IDX_289161CB53C674EE (offer_id), INDEX hash (hash), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE offer (id INT UNSIGNED AUTO_INCREMENT NOT NULL, category_id INT UNSIGNED NOT NULL, currency_id INT UNSIGNED NOT NULL, name VARCHAR(255) NOT NULL, geo_info LONGTEXT DEFAULT NULL, source_traffic LONGTEXT DEFAULT NULL, forbidden_sources LONGTEXT DEFAULT NULL, pay_sum DOUBLE PRECISION UNSIGNED DEFAULT \'0\' NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_29D6873E12469DE2 (category_id), INDEX IDX_29D6873E38248176 (currency_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE offer_pay_types (offer_id INT UNSIGNED NOT NULL, pay_types_id INT UNSIGNED NOT NULL, INDEX IDX_B2145B6D53C674EE (offer_id), INDEX IDX_B2145B6D82E2C2A8 (pay_types_id), PRIMARY KEY(offer_id, pay_types_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE offer_geo (offer_id INT UNSIGNED NOT NULL, geo_id INT UNSIGNED NOT NULL, INDEX IDX_5B6B860453C674EE (offer_id), INDEX IDX_5B6B8604FA49D0B (geo_id), PRIMARY KEY(offer_id, geo_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pay_types (id INT UNSIGNED AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pre_landing (id INT UNSIGNED AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, url VARCHAR(255) NOT NULL, cr INT UNSIGNED DEFAULT 0 NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pre_landing_page (id INT UNSIGNED AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, url VARCHAR(255) NOT NULL, cr INT UNSIGNED DEFAULT 0 NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE stream (id INT UNSIGNED AUTO_INCREMENT NOT NULL, user_id INT UNSIGNED NOT NULL, offer_id INT UNSIGNED NOT NULL, pre_landing_id INT UNSIGNED DEFAULT NULL, landing_id INT UNSIGNED DEFAULT NULL, pre_landing_page_id INT UNSIGNED DEFAULT NULL, geo_id INT UNSIGNED NOT NULL, pay_type_id INT UNSIGNED NOT NULL, name VARCHAR(255) NOT NULL, url VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_F0E9BE1CA76ED395 (user_id), INDEX IDX_F0E9BE1C53C674EE (offer_id), INDEX IDX_F0E9BE1CB698DEF7 (pre_landing_id), INDEX IDX_F0E9BE1CEFD98736 (landing_id), INDEX IDX_F0E9BE1CA743AD6C (pre_landing_page_id), INDEX IDX_F0E9BE1CFA49D0B (geo_id), INDEX IDX_F0E9BE1C23A64B58 (pay_type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT UNSIGNED AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, first_name VARCHAR(32) NOT NULL, balance DOUBLE PRECISION DEFAULT \'0\' NOT NULL, api_token VARCHAR(32) NOT NULL, activate INT UNSIGNED DEFAULT 0 NOT NULL, telegram VARCHAR(32) DEFAULT NULL, viber VARCHAR(32) DEFAULT NULL, skype VARCHAR(32) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), UNIQUE INDEX UNIQ_8D93D6497BA2F5EB (api_token), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE lead ADD CONSTRAINT FK_289161CBD0ED463E FOREIGN KEY (stream_id) REFERENCES stream (id)');
        $this->addSql('ALTER TABLE lead ADD CONSTRAINT FK_289161CBFA49D0B FOREIGN KEY (geo_id) REFERENCES geo (id)');
        $this->addSql('ALTER TABLE lead ADD CONSTRAINT FK_289161CB53C674EE FOREIGN KEY (offer_id) REFERENCES offer (id)');
        $this->addSql('ALTER TABLE offer ADD CONSTRAINT FK_29D6873E12469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE offer ADD CONSTRAINT FK_29D6873E38248176 FOREIGN KEY (currency_id) REFERENCES currency (id)');
        $this->addSql('ALTER TABLE offer_pay_types ADD CONSTRAINT FK_B2145B6D53C674EE FOREIGN KEY (offer_id) REFERENCES offer (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE offer_pay_types ADD CONSTRAINT FK_B2145B6D82E2C2A8 FOREIGN KEY (pay_types_id) REFERENCES pay_types (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE offer_geo ADD CONSTRAINT FK_5B6B860453C674EE FOREIGN KEY (offer_id) REFERENCES offer (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE offer_geo ADD CONSTRAINT FK_5B6B8604FA49D0B FOREIGN KEY (geo_id) REFERENCES geo (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE stream ADD CONSTRAINT FK_F0E9BE1CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE stream ADD CONSTRAINT FK_F0E9BE1C53C674EE FOREIGN KEY (offer_id) REFERENCES offer (id)');
        $this->addSql('ALTER TABLE stream ADD CONSTRAINT FK_F0E9BE1CB698DEF7 FOREIGN KEY (pre_landing_id) REFERENCES pre_landing (id)');
        $this->addSql('ALTER TABLE stream ADD CONSTRAINT FK_F0E9BE1CEFD98736 FOREIGN KEY (landing_id) REFERENCES landing (id)');
        $this->addSql('ALTER TABLE stream ADD CONSTRAINT FK_F0E9BE1CA743AD6C FOREIGN KEY (pre_landing_page_id) REFERENCES pre_landing_page (id)');
        $this->addSql('ALTER TABLE stream ADD CONSTRAINT FK_F0E9BE1CFA49D0B FOREIGN KEY (geo_id) REFERENCES geo (id)');
        $this->addSql('ALTER TABLE stream ADD CONSTRAINT FK_F0E9BE1C23A64B58 FOREIGN KEY (pay_type_id) REFERENCES pay_types (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE offer DROP FOREIGN KEY FK_29D6873E12469DE2');
        $this->addSql('ALTER TABLE offer DROP FOREIGN KEY FK_29D6873E38248176');
        $this->addSql('ALTER TABLE lead DROP FOREIGN KEY FK_289161CBFA49D0B');
        $this->addSql('ALTER TABLE offer_geo DROP FOREIGN KEY FK_5B6B8604FA49D0B');
        $this->addSql('ALTER TABLE stream DROP FOREIGN KEY FK_F0E9BE1CFA49D0B');
        $this->addSql('ALTER TABLE stream DROP FOREIGN KEY FK_F0E9BE1CEFD98736');
        $this->addSql('ALTER TABLE lead DROP FOREIGN KEY FK_289161CB53C674EE');
        $this->addSql('ALTER TABLE offer_pay_types DROP FOREIGN KEY FK_B2145B6D53C674EE');
        $this->addSql('ALTER TABLE offer_geo DROP FOREIGN KEY FK_5B6B860453C674EE');
        $this->addSql('ALTER TABLE stream DROP FOREIGN KEY FK_F0E9BE1C53C674EE');
        $this->addSql('ALTER TABLE offer_pay_types DROP FOREIGN KEY FK_B2145B6D82E2C2A8');
        $this->addSql('ALTER TABLE stream DROP FOREIGN KEY FK_F0E9BE1C23A64B58');
        $this->addSql('ALTER TABLE stream DROP FOREIGN KEY FK_F0E9BE1CB698DEF7');
        $this->addSql('ALTER TABLE stream DROP FOREIGN KEY FK_F0E9BE1CA743AD6C');
        $this->addSql('ALTER TABLE lead DROP FOREIGN KEY FK_289161CBD0ED463E');
        $this->addSql('ALTER TABLE stream DROP FOREIGN KEY FK_F0E9BE1CA76ED395');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE currency');
        $this->addSql('DROP TABLE geo');
        $this->addSql('DROP TABLE landing');
        $this->addSql('DROP TABLE lead');
        $this->addSql('DROP TABLE offer');
        $this->addSql('DROP TABLE offer_pay_types');
        $this->addSql('DROP TABLE offer_geo');
        $this->addSql('DROP TABLE pay_types');
        $this->addSql('DROP TABLE pre_landing');
        $this->addSql('DROP TABLE pre_landing_page');
        $this->addSql('DROP TABLE stream');
        $this->addSql('DROP TABLE user');
    }
}
