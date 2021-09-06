<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210906115541 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE balance_operations (id INT AUTO_INCREMENT NOT NULL, lead_id INT UNSIGNED NOT NULL, sum DOUBLE PRECISION NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_70B7556A55458D (lead_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category (id INT UNSIGNED AUTO_INCREMENT NOT NULL, tree_root INT UNSIGNED DEFAULT NULL, parent_id INT UNSIGNED DEFAULT NULL, name VARCHAR(255) NOT NULL, lft INT DEFAULT 0 NOT NULL, lvl INT DEFAULT 0 NOT NULL, rgt INT DEFAULT 0 NOT NULL, INDEX IDX_64C19C1A977936C (tree_root), INDEX IDX_64C19C1727ACA70 (parent_id), UNIQUE INDEX unique_name (name, parent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE currency (id INT UNSIGNED AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, sign VARCHAR(2) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE day_stats (id INT UNSIGNED AUTO_INCREMENT NOT NULL, stream_id INT UNSIGNED NOT NULL, user_id INT UNSIGNED NOT NULL, ip VARBINARY(16) NOT NULL COMMENT \'(DC2Type:ip)\', ua LONGTEXT NOT NULL, ua_hash VARCHAR(32) NOT NULL, visits INT UNSIGNED DEFAULT 1 NOT NULL, ref VARCHAR(255) DEFAULT NULL, pre_landing_visits INT UNSIGNED DEFAULT 0 NOT NULL, landing_visits INT UNSIGNED DEFAULT 0 NOT NULL, pre_landing_page_visits INT UNSIGNED DEFAULT 0 NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_EE8ADB4ED0ED463E (stream_id), INDEX IDX_EE8ADB4EA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ext_translations (id INT AUTO_INCREMENT NOT NULL, locale VARCHAR(8) NOT NULL, object_class VARCHAR(191) NOT NULL, field VARCHAR(32) NOT NULL, foreign_key VARCHAR(64) NOT NULL, content LONGTEXT DEFAULT NULL, INDEX translations_lookup_idx (locale, object_class, foreign_key), UNIQUE INDEX lookup_unique_idx (locale, object_class, field, foreign_key), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB ROW_FORMAT = DYNAMIC');
        $this->addSql('CREATE TABLE geo (id INT UNSIGNED AUTO_INCREMENT NOT NULL, name VARCHAR(3) NOT NULL, country VARCHAR(32) NOT NULL, image_filename VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE landing (id INT UNSIGNED AUTO_INCREMENT NOT NULL, category_id INT UNSIGNED NOT NULL, name VARCHAR(255) NOT NULL, url VARCHAR(255) NOT NULL, cr INT UNSIGNED DEFAULT 0, INDEX IDX_EF3ACE1512469DE2 (category_id), UNIQUE INDEX unique_name (name, category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE lead (id INT UNSIGNED AUTO_INCREMENT NOT NULL, stream_id INT UNSIGNED NOT NULL, geo_id INT UNSIGNED NOT NULL, offer_id INT UNSIGNED NOT NULL, user_id INT UNSIGNED NOT NULL, first_name VARCHAR(255) DEFAULT NULL, phone VARCHAR(255) NOT NULL, ip VARBINARY(16) NOT NULL COMMENT \'(DC2Type:ip)\', ua LONGTEXT DEFAULT NULL, hash VARCHAR(32) NOT NULL, status INT UNSIGNED DEFAULT 0 NOT NULL COMMENT \'0 - new, 1 - rejected, 2 - accepted, 3 - fake\', reject_type INT DEFAULT NULL, status_comment VARCHAR(255) DEFAULT NULL, utm_medium VARCHAR(255) DEFAULT NULL, utm_campaign VARCHAR(255) DEFAULT NULL, utm_content VARCHAR(255) DEFAULT NULL, utm_term VARCHAR(255) DEFAULT NULL, pay_status INT UNSIGNED DEFAULT 0 COMMENT \'0 - unpayed, 1 - payed\', unique_id VARCHAR(13) NOT NULL, gateway_status INT UNSIGNED DEFAULT 0 NOT NULL COMMENT \'0 - not sended, 1 - sended\', sum INT DEFAULT NULL COMMENT \'lead price for client\', full_request_data LONGTEXT DEFAULT NULL COMMENT \'serialize request data\', referer VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_289161CBE3C68343 (unique_id), INDEX IDX_289161CBD0ED463E (stream_id), INDEX IDX_289161CBFA49D0B (geo_id), INDEX IDX_289161CB53C674EE (offer_id), INDEX IDX_289161CBA76ED395 (user_id), INDEX hash (hash), INDEX streamIp (stream_id, ip), INDEX userDate (user_id, created_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE offer (id INT UNSIGNED AUTO_INCREMENT NOT NULL, category_id INT UNSIGNED NOT NULL, pay_type_id INT UNSIGNED NOT NULL, currency_id INT UNSIGNED NOT NULL, partner_id INT UNSIGNED NOT NULL, name VARCHAR(255) NOT NULL, geo_info LONGTEXT DEFAULT NULL, source_traffic LONGTEXT DEFAULT NULL, forbidden_sources LONGTEXT DEFAULT NULL, pay_sum DOUBLE PRECISION UNSIGNED DEFAULT \'0\' NOT NULL, image_filename VARCHAR(255) DEFAULT NULL, sum INT UNSIGNED DEFAULT 0 NOT NULL COMMENT \'Sum in lead for clients\', created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_29D6873E12469DE2 (category_id), INDEX IDX_29D6873E23A64B58 (pay_type_id), INDEX IDX_29D6873E38248176 (currency_id), INDEX IDX_29D6873E9393F8FE (partner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE offer_geo (offer_id INT UNSIGNED NOT NULL, geo_id INT UNSIGNED NOT NULL, INDEX IDX_5B6B860453C674EE (offer_id), INDEX IDX_5B6B8604FA49D0B (geo_id), PRIMARY KEY(offer_id, geo_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE offer_pre_landing (offer_id INT UNSIGNED NOT NULL, pre_landing_id INT UNSIGNED NOT NULL, INDEX IDX_E9F3E5E53C674EE (offer_id), INDEX IDX_E9F3E5EB698DEF7 (pre_landing_id), PRIMARY KEY(offer_id, pre_landing_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE offer_landing (offer_id INT UNSIGNED NOT NULL, landing_id INT UNSIGNED NOT NULL, INDEX IDX_4E32081C53C674EE (offer_id), INDEX IDX_4E32081CEFD98736 (landing_id), PRIMARY KEY(offer_id, landing_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE offer_pre_landing_page (offer_id INT UNSIGNED NOT NULL, pre_landing_page_id INT UNSIGNED NOT NULL, INDEX IDX_B634EA4053C674EE (offer_id), INDEX IDX_B634EA40A743AD6C (pre_landing_page_id), PRIMARY KEY(offer_id, pre_landing_page_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE partner_additional_params (id INT AUTO_INCREMENT NOT NULL, partner_id INT UNSIGNED NOT NULL, value_name VARCHAR(255) NOT NULL, value VARCHAR(255) NOT NULL, INDEX IDX_7278D3279393F8FE (partner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE partners (id INT UNSIGNED AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, http_server_send VARCHAR(255) NOT NULL, api_key VARCHAR(255) DEFAULT NULL, data_format VARCHAR(5) DEFAULT \'xml\' NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pay_types (id INT UNSIGNED AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE postback (id INT UNSIGNED AUTO_INCREMENT NOT NULL, user_id INT UNSIGNED DEFAULT NULL, lead_create VARCHAR(255) DEFAULT NULL, lead_approve VARCHAR(255) DEFAULT NULL, lead_decline VARCHAR(255) DEFAULT NULL, lead_trash VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_79E255BEA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pre_landing (id INT UNSIGNED AUTO_INCREMENT NOT NULL, category_id INT UNSIGNED NOT NULL, name VARCHAR(255) NOT NULL, url VARCHAR(255) NOT NULL, cr INT UNSIGNED DEFAULT 0 NOT NULL, INDEX IDX_8FBD825012469DE2 (category_id), UNIQUE INDEX unique_name (name, category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pre_landing_page (id INT UNSIGNED AUTO_INCREMENT NOT NULL, category_id INT UNSIGNED NOT NULL, name VARCHAR(255) NOT NULL, url VARCHAR(255) NOT NULL, cr INT UNSIGNED DEFAULT 0 NOT NULL, INDEX IDX_47F19E8712469DE2 (category_id), UNIQUE INDEX unique_name (name, category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE stats (id INT UNSIGNED AUTO_INCREMENT NOT NULL, user_id INT UNSIGNED NOT NULL, stream_id INT UNSIGNED DEFAULT NULL, offer_id INT UNSIGNED DEFAULT NULL, day DATE NOT NULL, pre_landing_unique_visits INT UNSIGNED DEFAULT 0 NOT NULL, landing_unique_visits INT UNSIGNED DEFAULT 0 NOT NULL, pre_landing_page_unique_visits INT UNSIGNED DEFAULT 0 NOT NULL, pre_landing_visits INT UNSIGNED DEFAULT 0 NOT NULL, landing_visits INT UNSIGNED DEFAULT 0 NOT NULL, pre_landing_page_visits INT UNSIGNED DEFAULT 0 NOT NULL, new_lead_count INT UNSIGNED DEFAULT 0 NOT NULL, rejected_lead_count INT UNSIGNED DEFAULT 0 NOT NULL, accepted_lead_count INT UNSIGNED DEFAULT 0 NOT NULL, fake_lead_count INT UNSIGNED DEFAULT 0 NOT NULL, INDEX IDX_574767AAA76ED395 (user_id), INDEX IDX_574767AAD0ED463E (stream_id), INDEX IDX_574767AA53C674EE (offer_id), INDEX dayUser (day, user_id), UNIQUE INDEX unique_data (day, user_id, offer_id, stream_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE stream (id INT UNSIGNED AUTO_INCREMENT NOT NULL, user_id INT UNSIGNED NOT NULL, offer_id INT UNSIGNED NOT NULL, pre_landing_id INT UNSIGNED DEFAULT NULL, landing_id INT UNSIGNED DEFAULT NULL, pre_landing_page_id INT UNSIGNED DEFAULT NULL, geo_id INT UNSIGNED NOT NULL, pay_type_id INT UNSIGNED NOT NULL, name VARCHAR(255) NOT NULL, url VARCHAR(255) NOT NULL, unique_id VARCHAR(13) NOT NULL, source_traffic INT NOT NULL, postback_create VARCHAR(255) DEFAULT NULL, postback_approve VARCHAR(255) DEFAULT NULL, postback_decline VARCHAR(255) DEFAULT NULL, postback_trash VARCHAR(255) DEFAULT NULL, google_tag_id VARCHAR(255) DEFAULT NULL, google_tag_conversion_id VARCHAR(255) DEFAULT NULL, sum INT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_F0E9BE1CA76ED395 (user_id), INDEX IDX_F0E9BE1C53C674EE (offer_id), INDEX IDX_F0E9BE1CB698DEF7 (pre_landing_id), INDEX IDX_F0E9BE1CEFD98736 (landing_id), INDEX IDX_F0E9BE1CA743AD6C (pre_landing_page_id), INDEX IDX_F0E9BE1CFA49D0B (geo_id), INDEX IDX_F0E9BE1C23A64B58 (pay_type_id), UNIQUE INDEX unique_id (unique_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT UNSIGNED AUTO_INCREMENT NOT NULL, manager_id INT UNSIGNED DEFAULT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, first_name VARCHAR(32) NOT NULL, balance DOUBLE PRECISION DEFAULT \'0\' NOT NULL, api_token VARCHAR(32) NOT NULL, activate INT UNSIGNED DEFAULT 0 NOT NULL, telegram VARCHAR(32) DEFAULT NULL, viber VARCHAR(32) DEFAULT NULL, skype VARCHAR(32) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), UNIQUE INDEX UNIQ_8D93D6497BA2F5EB (api_token), INDEX IDX_8D93D649783E3463 (manager_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE balance_operations ADD CONSTRAINT FK_70B7556A55458D FOREIGN KEY (lead_id) REFERENCES lead (id)');
        $this->addSql('ALTER TABLE category ADD CONSTRAINT FK_64C19C1A977936C FOREIGN KEY (tree_root) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE category ADD CONSTRAINT FK_64C19C1727ACA70 FOREIGN KEY (parent_id) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE day_stats ADD CONSTRAINT FK_EE8ADB4ED0ED463E FOREIGN KEY (stream_id) REFERENCES stream (id)');
        $this->addSql('ALTER TABLE day_stats ADD CONSTRAINT FK_EE8ADB4EA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE landing ADD CONSTRAINT FK_EF3ACE1512469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE lead ADD CONSTRAINT FK_289161CBD0ED463E FOREIGN KEY (stream_id) REFERENCES stream (id)');
        $this->addSql('ALTER TABLE lead ADD CONSTRAINT FK_289161CBFA49D0B FOREIGN KEY (geo_id) REFERENCES geo (id)');
        $this->addSql('ALTER TABLE lead ADD CONSTRAINT FK_289161CB53C674EE FOREIGN KEY (offer_id) REFERENCES offer (id)');
        $this->addSql('ALTER TABLE lead ADD CONSTRAINT FK_289161CBA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE offer ADD CONSTRAINT FK_29D6873E12469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE offer ADD CONSTRAINT FK_29D6873E23A64B58 FOREIGN KEY (pay_type_id) REFERENCES pay_types (id)');
        $this->addSql('ALTER TABLE offer ADD CONSTRAINT FK_29D6873E38248176 FOREIGN KEY (currency_id) REFERENCES currency (id)');
        $this->addSql('ALTER TABLE offer ADD CONSTRAINT FK_29D6873E9393F8FE FOREIGN KEY (partner_id) REFERENCES partners (id)');
        $this->addSql('ALTER TABLE offer_geo ADD CONSTRAINT FK_5B6B860453C674EE FOREIGN KEY (offer_id) REFERENCES offer (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE offer_geo ADD CONSTRAINT FK_5B6B8604FA49D0B FOREIGN KEY (geo_id) REFERENCES geo (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE offer_pre_landing ADD CONSTRAINT FK_E9F3E5E53C674EE FOREIGN KEY (offer_id) REFERENCES offer (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE offer_pre_landing ADD CONSTRAINT FK_E9F3E5EB698DEF7 FOREIGN KEY (pre_landing_id) REFERENCES pre_landing (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE offer_landing ADD CONSTRAINT FK_4E32081C53C674EE FOREIGN KEY (offer_id) REFERENCES offer (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE offer_landing ADD CONSTRAINT FK_4E32081CEFD98736 FOREIGN KEY (landing_id) REFERENCES landing (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE offer_pre_landing_page ADD CONSTRAINT FK_B634EA4053C674EE FOREIGN KEY (offer_id) REFERENCES offer (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE offer_pre_landing_page ADD CONSTRAINT FK_B634EA40A743AD6C FOREIGN KEY (pre_landing_page_id) REFERENCES pre_landing_page (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE partner_additional_params ADD CONSTRAINT FK_7278D3279393F8FE FOREIGN KEY (partner_id) REFERENCES partners (id)');
        $this->addSql('ALTER TABLE postback ADD CONSTRAINT FK_79E255BEA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE pre_landing ADD CONSTRAINT FK_8FBD825012469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE pre_landing_page ADD CONSTRAINT FK_47F19E8712469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE stats ADD CONSTRAINT FK_574767AAA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE stats ADD CONSTRAINT FK_574767AAD0ED463E FOREIGN KEY (stream_id) REFERENCES stream (id)');
        $this->addSql('ALTER TABLE stats ADD CONSTRAINT FK_574767AA53C674EE FOREIGN KEY (offer_id) REFERENCES offer (id)');
        $this->addSql('ALTER TABLE stream ADD CONSTRAINT FK_F0E9BE1CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE stream ADD CONSTRAINT FK_F0E9BE1C53C674EE FOREIGN KEY (offer_id) REFERENCES offer (id)');
        $this->addSql('ALTER TABLE stream ADD CONSTRAINT FK_F0E9BE1CB698DEF7 FOREIGN KEY (pre_landing_id) REFERENCES pre_landing (id)');
        $this->addSql('ALTER TABLE stream ADD CONSTRAINT FK_F0E9BE1CEFD98736 FOREIGN KEY (landing_id) REFERENCES landing (id)');
        $this->addSql('ALTER TABLE stream ADD CONSTRAINT FK_F0E9BE1CA743AD6C FOREIGN KEY (pre_landing_page_id) REFERENCES pre_landing_page (id)');
        $this->addSql('ALTER TABLE stream ADD CONSTRAINT FK_F0E9BE1CFA49D0B FOREIGN KEY (geo_id) REFERENCES geo (id)');
        $this->addSql('ALTER TABLE stream ADD CONSTRAINT FK_F0E9BE1C23A64B58 FOREIGN KEY (pay_type_id) REFERENCES pay_types (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649783E3463 FOREIGN KEY (manager_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE category DROP FOREIGN KEY FK_64C19C1A977936C');
        $this->addSql('ALTER TABLE category DROP FOREIGN KEY FK_64C19C1727ACA70');
        $this->addSql('ALTER TABLE landing DROP FOREIGN KEY FK_EF3ACE1512469DE2');
        $this->addSql('ALTER TABLE offer DROP FOREIGN KEY FK_29D6873E12469DE2');
        $this->addSql('ALTER TABLE pre_landing DROP FOREIGN KEY FK_8FBD825012469DE2');
        $this->addSql('ALTER TABLE pre_landing_page DROP FOREIGN KEY FK_47F19E8712469DE2');
        $this->addSql('ALTER TABLE offer DROP FOREIGN KEY FK_29D6873E38248176');
        $this->addSql('ALTER TABLE lead DROP FOREIGN KEY FK_289161CBFA49D0B');
        $this->addSql('ALTER TABLE offer_geo DROP FOREIGN KEY FK_5B6B8604FA49D0B');
        $this->addSql('ALTER TABLE stream DROP FOREIGN KEY FK_F0E9BE1CFA49D0B');
        $this->addSql('ALTER TABLE offer_landing DROP FOREIGN KEY FK_4E32081CEFD98736');
        $this->addSql('ALTER TABLE stream DROP FOREIGN KEY FK_F0E9BE1CEFD98736');
        $this->addSql('ALTER TABLE balance_operations DROP FOREIGN KEY FK_70B7556A55458D');
        $this->addSql('ALTER TABLE lead DROP FOREIGN KEY FK_289161CB53C674EE');
        $this->addSql('ALTER TABLE offer_geo DROP FOREIGN KEY FK_5B6B860453C674EE');
        $this->addSql('ALTER TABLE offer_pre_landing DROP FOREIGN KEY FK_E9F3E5E53C674EE');
        $this->addSql('ALTER TABLE offer_landing DROP FOREIGN KEY FK_4E32081C53C674EE');
        $this->addSql('ALTER TABLE offer_pre_landing_page DROP FOREIGN KEY FK_B634EA4053C674EE');
        $this->addSql('ALTER TABLE stats DROP FOREIGN KEY FK_574767AA53C674EE');
        $this->addSql('ALTER TABLE stream DROP FOREIGN KEY FK_F0E9BE1C53C674EE');
        $this->addSql('ALTER TABLE offer DROP FOREIGN KEY FK_29D6873E9393F8FE');
        $this->addSql('ALTER TABLE partner_additional_params DROP FOREIGN KEY FK_7278D3279393F8FE');
        $this->addSql('ALTER TABLE offer DROP FOREIGN KEY FK_29D6873E23A64B58');
        $this->addSql('ALTER TABLE stream DROP FOREIGN KEY FK_F0E9BE1C23A64B58');
        $this->addSql('ALTER TABLE offer_pre_landing DROP FOREIGN KEY FK_E9F3E5EB698DEF7');
        $this->addSql('ALTER TABLE stream DROP FOREIGN KEY FK_F0E9BE1CB698DEF7');
        $this->addSql('ALTER TABLE offer_pre_landing_page DROP FOREIGN KEY FK_B634EA40A743AD6C');
        $this->addSql('ALTER TABLE stream DROP FOREIGN KEY FK_F0E9BE1CA743AD6C');
        $this->addSql('ALTER TABLE day_stats DROP FOREIGN KEY FK_EE8ADB4ED0ED463E');
        $this->addSql('ALTER TABLE lead DROP FOREIGN KEY FK_289161CBD0ED463E');
        $this->addSql('ALTER TABLE stats DROP FOREIGN KEY FK_574767AAD0ED463E');
        $this->addSql('ALTER TABLE day_stats DROP FOREIGN KEY FK_EE8ADB4EA76ED395');
        $this->addSql('ALTER TABLE lead DROP FOREIGN KEY FK_289161CBA76ED395');
        $this->addSql('ALTER TABLE postback DROP FOREIGN KEY FK_79E255BEA76ED395');
        $this->addSql('ALTER TABLE stats DROP FOREIGN KEY FK_574767AAA76ED395');
        $this->addSql('ALTER TABLE stream DROP FOREIGN KEY FK_F0E9BE1CA76ED395');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649783E3463');
        $this->addSql('DROP TABLE balance_operations');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE currency');
        $this->addSql('DROP TABLE day_stats');
        $this->addSql('DROP TABLE ext_translations');
        $this->addSql('DROP TABLE geo');
        $this->addSql('DROP TABLE landing');
        $this->addSql('DROP TABLE lead');
        $this->addSql('DROP TABLE offer');
        $this->addSql('DROP TABLE offer_geo');
        $this->addSql('DROP TABLE offer_pre_landing');
        $this->addSql('DROP TABLE offer_landing');
        $this->addSql('DROP TABLE offer_pre_landing_page');
        $this->addSql('DROP TABLE partner_additional_params');
        $this->addSql('DROP TABLE partners');
        $this->addSql('DROP TABLE pay_types');
        $this->addSql('DROP TABLE postback');
        $this->addSql('DROP TABLE pre_landing');
        $this->addSql('DROP TABLE pre_landing_page');
        $this->addSql('DROP TABLE stats');
        $this->addSql('DROP TABLE stream');
        $this->addSql('DROP TABLE user');
    }
}
