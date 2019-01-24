<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190122201510 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE media_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE country_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE url_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE place_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE hashtag_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE tweeter_user_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE place_type_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE tweet_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE geo_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE media (id INT NOT NULL, type VARCHAR(50) NOT NULL, url VARCHAR(100) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE country (id INT NOT NULL, code VARCHAR(20) NOT NULL, name VARCHAR(50) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE url (id INT NOT NULL, url TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE place (id INT NOT NULL, id_country_id INT DEFAULT NULL, id_place_type_id INT DEFAULT NULL, id_place VARCHAR(50) NOT NULL, full_name VARCHAR(120) NOT NULL, name VARCHAR(100) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_741D53CD5CA5BEA7 ON place (id_country_id)');
        $this->addSql('CREATE INDEX IDX_741D53CD4FA84A0D ON place (id_place_type_id)');
        $this->addSql('CREATE TABLE hashtag (id INT NOT NULL, name VARCHAR(100) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE tweeter_user (id INT NOT NULL, friends_count INT DEFAULT NULL, screen_name VARCHAR(100) DEFAULT NULL, followers_count VARCHAR(100) DEFAULT NULL, name VARCHAR(100) NOT NULL, location VARCHAR(100) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE place_type (id INT NOT NULL, name VARCHAR(40) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE tweet (id INT NOT NULL, place_id INT DEFAULT NULL, geo_id INT DEFAULT NULL, tweet_user_id INT DEFAULT NULL, source VARCHAR(100) DEFAULT NULL, link VARCHAR(150) NOT NULL, retweet_count INT NOT NULL, favourite_count INT NOT NULL, tweet_text VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_3D660A3BDA6A219 ON tweet (place_id)');
        $this->addSql('CREATE INDEX IDX_3D660A3BFA49D0B ON tweet (geo_id)');
        $this->addSql('CREATE INDEX IDX_3D660A3B28EDB2FD ON tweet (tweet_user_id)');
        $this->addSql('CREATE TABLE tweet_hashtag (tweet_id INT NOT NULL, hashtag_id INT NOT NULL, PRIMARY KEY(tweet_id, hashtag_id))');
        $this->addSql('CREATE INDEX IDX_D5364B091041E39B ON tweet_hashtag (tweet_id)');
        $this->addSql('CREATE INDEX IDX_D5364B09FB34EF56 ON tweet_hashtag (hashtag_id)');
        $this->addSql('CREATE TABLE tweet_media (tweet_id INT NOT NULL, media_id INT NOT NULL, PRIMARY KEY(tweet_id, media_id))');
        $this->addSql('CREATE INDEX IDX_F6E77C431041E39B ON tweet_media (tweet_id)');
        $this->addSql('CREATE INDEX IDX_F6E77C43EA9FDD75 ON tweet_media (media_id)');
        $this->addSql('CREATE TABLE tweet_url (tweet_id INT NOT NULL, url_id INT NOT NULL, PRIMARY KEY(tweet_id, url_id))');
        $this->addSql('CREATE INDEX IDX_F86F26121041E39B ON tweet_url (tweet_id)');
        $this->addSql('CREATE INDEX IDX_F86F261281CFDAE7 ON tweet_url (url_id)');
        $this->addSql('CREATE TABLE tweet_tweeter_user (tweet_id INT NOT NULL, tweeter_user_id INT NOT NULL, PRIMARY KEY(tweet_id, tweeter_user_id))');
        $this->addSql('CREATE INDEX IDX_7EE0BC511041E39B ON tweet_tweeter_user (tweet_id)');
        $this->addSql('CREATE INDEX IDX_7EE0BC5166183F6F ON tweet_tweeter_user (tweeter_user_id)');
        $this->addSql('CREATE TABLE geo (id INT NOT NULL, type VARCHAR(20) NOT NULL, latitude DOUBLE PRECISION NOT NULL, longitude DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE place ADD CONSTRAINT FK_741D53CD5CA5BEA7 FOREIGN KEY (id_country_id) REFERENCES country (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE place ADD CONSTRAINT FK_741D53CD4FA84A0D FOREIGN KEY (id_place_type_id) REFERENCES place_type (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE tweet ADD CONSTRAINT FK_3D660A3BDA6A219 FOREIGN KEY (place_id) REFERENCES place (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE tweet ADD CONSTRAINT FK_3D660A3BFA49D0B FOREIGN KEY (geo_id) REFERENCES geo (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE tweet ADD CONSTRAINT FK_3D660A3B28EDB2FD FOREIGN KEY (tweet_user_id) REFERENCES tweeter_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE tweet_hashtag ADD CONSTRAINT FK_D5364B091041E39B FOREIGN KEY (tweet_id) REFERENCES tweet (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE tweet_hashtag ADD CONSTRAINT FK_D5364B09FB34EF56 FOREIGN KEY (hashtag_id) REFERENCES hashtag (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE tweet_media ADD CONSTRAINT FK_F6E77C431041E39B FOREIGN KEY (tweet_id) REFERENCES tweet (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE tweet_media ADD CONSTRAINT FK_F6E77C43EA9FDD75 FOREIGN KEY (media_id) REFERENCES media (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE tweet_url ADD CONSTRAINT FK_F86F26121041E39B FOREIGN KEY (tweet_id) REFERENCES tweet (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE tweet_url ADD CONSTRAINT FK_F86F261281CFDAE7 FOREIGN KEY (url_id) REFERENCES url (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE tweet_tweeter_user ADD CONSTRAINT FK_7EE0BC511041E39B FOREIGN KEY (tweet_id) REFERENCES tweet (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE tweet_tweeter_user ADD CONSTRAINT FK_7EE0BC5166183F6F FOREIGN KEY (tweeter_user_id) REFERENCES tweeter_user (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE tweet_media DROP CONSTRAINT FK_F6E77C43EA9FDD75');
        $this->addSql('ALTER TABLE place DROP CONSTRAINT FK_741D53CD5CA5BEA7');
        $this->addSql('ALTER TABLE tweet_url DROP CONSTRAINT FK_F86F261281CFDAE7');
        $this->addSql('ALTER TABLE tweet DROP CONSTRAINT FK_3D660A3BDA6A219');
        $this->addSql('ALTER TABLE tweet_hashtag DROP CONSTRAINT FK_D5364B09FB34EF56');
        $this->addSql('ALTER TABLE tweet DROP CONSTRAINT FK_3D660A3B28EDB2FD');
        $this->addSql('ALTER TABLE tweet_tweeter_user DROP CONSTRAINT FK_7EE0BC5166183F6F');
        $this->addSql('ALTER TABLE place DROP CONSTRAINT FK_741D53CD4FA84A0D');
        $this->addSql('ALTER TABLE tweet_hashtag DROP CONSTRAINT FK_D5364B091041E39B');
        $this->addSql('ALTER TABLE tweet_media DROP CONSTRAINT FK_F6E77C431041E39B');
        $this->addSql('ALTER TABLE tweet_url DROP CONSTRAINT FK_F86F26121041E39B');
        $this->addSql('ALTER TABLE tweet_tweeter_user DROP CONSTRAINT FK_7EE0BC511041E39B');
        $this->addSql('ALTER TABLE tweet DROP CONSTRAINT FK_3D660A3BFA49D0B');
        $this->addSql('DROP SEQUENCE media_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE country_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE url_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE place_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE hashtag_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE tweeter_user_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE place_type_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE tweet_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE geo_id_seq CASCADE');
        $this->addSql('DROP TABLE media');
        $this->addSql('DROP TABLE country');
        $this->addSql('DROP TABLE url');
        $this->addSql('DROP TABLE place');
        $this->addSql('DROP TABLE hashtag');
        $this->addSql('DROP TABLE tweeter_user');
        $this->addSql('DROP TABLE place_type');
        $this->addSql('DROP TABLE tweet');
        $this->addSql('DROP TABLE tweet_hashtag');
        $this->addSql('DROP TABLE tweet_media');
        $this->addSql('DROP TABLE tweet_url');
        $this->addSql('DROP TABLE tweet_tweeter_user');
        $this->addSql('DROP TABLE geo');
    }
}
