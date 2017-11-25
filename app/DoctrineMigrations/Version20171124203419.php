<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171124203419 extends AbstractGeneratedMigration
{
    public function up_MySQL(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE fr_bundle_cached_resource (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', url VARCHAR(254) NOT NULL, last_checked DATETIME NOT NULL, cached_url VARCHAR(128) NOT NULL, active TINYINT(1) DEFAULT \'0\' NOT NULL, UNIQUE INDEX UNIQ_90699E48F47645AE (url), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE feed_source (id VARCHAR(255) NOT NULL, news_board_id VARCHAR(255) DEFAULT NULL, collector_name VARCHAR(24) NOT NULL, source_data LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', scraping_specification LONGTEXT DEFAULT \'{}\' NOT NULL COMMENT \'(DC2Type:json)\', default_language VARCHAR(3) NOT NULL, last_collection_date DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', title VARCHAR(64) NOT NULL, description TEXT DEFAULT NULL, enabled TINYINT(1) DEFAULT \'1\' NOT NULL, icon VARCHAR(255) DEFAULT NULL, INDEX IDX_9DA80F87AED2F4D4 (news_board_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE feed_entry (id VARCHAR(255) NOT NULL, feed_source_id VARCHAR(255) DEFAULT NULL, title VARCHAR(254) NOT NULL COMMENT \'Publication\'\'s title\', content LONGTEXT NOT NULL, full_content LONGTEXT NOT NULL, source_url VARCHAR(254) NOT NULL, date DATETIME NOT NULL COMMENT \'Publication\'\'s date (comes from external source)(DC2Type:datetime_immutable)\', collection_date DATETIME NOT NULL COMMENT \'Date when the entry was fetched by the collector(DC2Type:datetime_immutable)\', language VARCHAR(255) NOT NULL, tags LONGTEXT NOT NULL COMMENT \'(DC2Type:json_array)\', icon VARCHAR(255) DEFAULT NULL, INDEX IDX_DEAECECCDDAEFFBD (feed_source_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE news_board (id VARCHAR(255) NOT NULL, name VARCHAR(48) NOT NULL, description VARCHAR(256) NOT NULL, icon VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE feed_source ADD CONSTRAINT FK_9DA80F87AED2F4D4 FOREIGN KEY (news_board_id) REFERENCES news_board (id)');
        $this->addSql('ALTER TABLE feed_entry ADD CONSTRAINT FK_DEAECECCDDAEFFBD FOREIGN KEY (feed_source_id) REFERENCES feed_source (id)');
    }
    
    public function up_SQLite(Schema $schema)
    {
        $this->addSql('CREATE TABLE fr_bundle_cached_resource (id CHAR(36) NOT NULL --(DC2Type:guid)
        , url VARCHAR(254) NOT NULL, last_checked DATETIME NOT NULL, cached_url VARCHAR(128) NOT NULL, active BOOLEAN DEFAULT \'0\' NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_90699E48F47645AE ON fr_bundle_cached_resource (url)');
        $this->addSql('CREATE TABLE feed_source (id VARCHAR(255) NOT NULL, news_board_id VARCHAR(255) DEFAULT NULL, collector_name VARCHAR(24) NOT NULL, source_data CLOB NOT NULL --(DC2Type:json)
        , scraping_specification CLOB DEFAULT \'{}\' NOT NULL --(DC2Type:json)
        , default_language VARCHAR(3) NOT NULL, last_collection_date DATETIME DEFAULT NULL --(DC2Type:datetime_immutable)
        , title VARCHAR(64) NOT NULL, description CLOB DEFAULT NULL, enabled BOOLEAN DEFAULT \'1\' NOT NULL, icon VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_9DA80F87AED2F4D4 ON feed_source (news_board_id)');
        $this->addSql('CREATE TABLE feed_entry (id VARCHAR(255) NOT NULL, feed_source_id VARCHAR(255) DEFAULT NULL, title VARCHAR(254) NOT NULL --Publication\'s title
        , content CLOB NOT NULL, full_content CLOB NOT NULL, source_url VARCHAR(254) NOT NULL, date DATETIME NOT NULL --Publication\'s date (comes from external source)(DC2Type:datetime_immutable)
        , collection_date DATETIME NOT NULL --Date when the entry was fetched by the collector(DC2Type:datetime_immutable)
        , language VARCHAR(255) NOT NULL, tags CLOB NOT NULL --(DC2Type:json_array)
        , icon VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_DEAECECCDDAEFFBD ON feed_entry (feed_source_id)');
        $this->addSql('CREATE TABLE news_board (id VARCHAR(255) NOT NULL, name VARCHAR(48) NOT NULL, description VARCHAR(256) NOT NULL, icon VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
    }

    public function down_MySQL(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE feed_entry DROP FOREIGN KEY FK_DEAECECCDDAEFFBD');
        $this->addSql('ALTER TABLE feed_source DROP FOREIGN KEY FK_9DA80F87AED2F4D4');
        $this->addSql('DROP TABLE fr_bundle_cached_resource');
        $this->addSql('DROP TABLE feed_source');
        $this->addSql('DROP TABLE feed_entry');
        $this->addSql('DROP TABLE news_board');
    }
    
    public function down_SQLite(Schema $schema)
    {
        $this->addSql('DROP TABLE fr_bundle_cached_resource');
        $this->addSql('DROP TABLE feed_source');
        $this->addSql('DROP TABLE feed_entry');
        $this->addSql('DROP TABLE news_board');
    }
}
