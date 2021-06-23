<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210622151337 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE adverts_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE categories_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE cities_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE photos_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE regions_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE users_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE adverts (id INT NOT NULL, category_id INT DEFAULT NULL, city_id INT DEFAULT NULL, user_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, description TEXT NOT NULL, publish_at TIMESTAMP(0) WITH TIME ZONE NOT NULL, cost BIGINT NOT NULL, count_views INT NOT NULL, status SMALLINT NOT NULL, created_at TIMESTAMP(0) WITH TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITH TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_8C88E77712469DE2 ON adverts (category_id)');
        $this->addSql('CREATE INDEX IDX_8C88E7778BAC62AF ON adverts (city_id)');
        $this->addSql('CREATE INDEX IDX_8C88E777A76ED395 ON adverts (user_id)');
        $this->addSql('CREATE TABLE categories (id INT NOT NULL, name VARCHAR(128) NOT NULL, url_code VARCHAR(64) NOT NULL, description TEXT NOT NULL, parent_id INT NOT NULL, created_at TIMESTAMP(0) WITH TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITH TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_3AF346686941F920 ON categories (url_code)');
        $this->addSql('CREATE TABLE cities (id INT NOT NULL, region_id INT DEFAULT NULL, name VARCHAR(64) NOT NULL, created_at TIMESTAMP(0) WITH TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITH TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_D95DB16B98260155 ON cities (region_id)');
        $this->addSql('CREATE TABLE photos (id INT NOT NULL, advert_id INT DEFAULT NULL, url VARCHAR(64) NOT NULL, created_at TIMESTAMP(0) WITH TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITH TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_876E0D9F47645AE ON photos (url)');
        $this->addSql('CREATE INDEX IDX_876E0D9D07ECCB6 ON photos (advert_id)');
        $this->addSql('CREATE TABLE regions (id INT NOT NULL, name VARCHAR(64) NOT NULL, created_at TIMESTAMP(0) WITH TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITH TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE users (id INT NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, patronymic VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, phone_number VARCHAR(13) NOT NULL, when_convenient_receive_calls VARCHAR(255) NOT NULL, status SMALLINT NOT NULL, created_at TIMESTAMP(0) WITH TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITH TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E9E7927C74 ON users (email)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E96B01BC5B ON users (phone_number)');
        $this->addSql('ALTER TABLE adverts ADD CONSTRAINT FK_8C88E77712469DE2 FOREIGN KEY (category_id) REFERENCES categories (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE adverts ADD CONSTRAINT FK_8C88E7778BAC62AF FOREIGN KEY (city_id) REFERENCES cities (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE adverts ADD CONSTRAINT FK_8C88E777A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE cities ADD CONSTRAINT FK_D95DB16B98260155 FOREIGN KEY (region_id) REFERENCES regions (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE photos ADD CONSTRAINT FK_876E0D9D07ECCB6 FOREIGN KEY (advert_id) REFERENCES adverts (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
//        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE photos DROP CONSTRAINT FK_876E0D9D07ECCB6');
        $this->addSql('ALTER TABLE adverts DROP CONSTRAINT FK_8C88E77712469DE2');
        $this->addSql('ALTER TABLE adverts DROP CONSTRAINT FK_8C88E7778BAC62AF');
        $this->addSql('ALTER TABLE cities DROP CONSTRAINT FK_D95DB16B98260155');
        $this->addSql('ALTER TABLE adverts DROP CONSTRAINT FK_8C88E777A76ED395');
        $this->addSql('DROP SEQUENCE adverts_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE categories_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE cities_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE photos_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE regions_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE users_id_seq CASCADE');
        $this->addSql('DROP TABLE adverts');
        $this->addSql('DROP TABLE categories');
        $this->addSql('DROP TABLE cities');
        $this->addSql('DROP TABLE photos');
        $this->addSql('DROP TABLE regions');
        $this->addSql('DROP TABLE users');
    }
}
