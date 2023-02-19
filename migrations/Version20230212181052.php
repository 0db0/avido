<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230212181052 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE moderation_decision_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE moderation_decision (id INT NOT NULL, advert_id INT NOT NULL, note TEXT DEFAULT NULL, status SMALLINT NOT NULL, created_at VARCHAR(255) NOT NULL, updated_at VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_E4377356D07ECCB6 ON moderation_decision (advert_id)');
        $this->addSql('ALTER TABLE moderation_decision ADD CONSTRAINT FK_E4377356D07ECCB6 FOREIGN KEY (advert_id) REFERENCES adverts (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE adverts ALTER published_at TYPE TIMESTAMP(6) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE adverts ALTER created_at TYPE TIMESTAMP(6) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE adverts ALTER updated_at TYPE TIMESTAMP(6) WITHOUT TIME ZONE');
        $this->addSql('COMMENT ON COLUMN adverts.published_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN adverts.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN adverts.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE categories ALTER created_at TYPE TIMESTAMP(6) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE categories ALTER updated_at TYPE TIMESTAMP(6) WITHOUT TIME ZONE');
        $this->addSql('COMMENT ON COLUMN categories.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN categories.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE cities ALTER created_at TYPE TIMESTAMP(6) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE cities ALTER updated_at TYPE TIMESTAMP(6) WITHOUT TIME ZONE');
        $this->addSql('COMMENT ON COLUMN cities.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN cities.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE email_verifications ALTER code TYPE VARCHAR(80)');
        $this->addSql('ALTER TABLE email_verifications ALTER verified_at TYPE TIMESTAMP(6) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE email_verifications ALTER created_at TYPE TIMESTAMP(6) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE email_verifications ALTER updated_at TYPE TIMESTAMP(6) WITHOUT TIME ZONE');
        $this->addSql('COMMENT ON COLUMN email_verifications.verified_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN email_verifications.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN email_verifications.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE photos ALTER created_at TYPE TIMESTAMP(6) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE photos ALTER updated_at TYPE TIMESTAMP(6) WITHOUT TIME ZONE');
        $this->addSql('COMMENT ON COLUMN photos.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN photos.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE regions ALTER created_at TYPE TIMESTAMP(6) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE regions ALTER updated_at TYPE TIMESTAMP(6) WITHOUT TIME ZONE');
        $this->addSql('COMMENT ON COLUMN regions.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN regions.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE users ALTER created_at TYPE TIMESTAMP(6) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE users ALTER updated_at TYPE TIMESTAMP(6) WITHOUT TIME ZONE');
        $this->addSql('COMMENT ON COLUMN users.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN users.updated_at IS \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE moderation_decision_id_seq CASCADE');
        $this->addSql('ALTER TABLE moderation_decision DROP CONSTRAINT FK_E4377356D07ECCB6');
        $this->addSql('DROP TABLE moderation_decision');
        $this->addSql('ALTER TABLE users ALTER created_at TYPE TIMESTAMP(6) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE users ALTER updated_at TYPE TIMESTAMP(6) WITHOUT TIME ZONE');
        $this->addSql('COMMENT ON COLUMN users.created_at IS NULL');
        $this->addSql('COMMENT ON COLUMN users.updated_at IS NULL');
        $this->addSql('ALTER TABLE regions ALTER created_at TYPE TIMESTAMP(6) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE regions ALTER updated_at TYPE TIMESTAMP(6) WITHOUT TIME ZONE');
        $this->addSql('COMMENT ON COLUMN regions.created_at IS NULL');
        $this->addSql('COMMENT ON COLUMN regions.updated_at IS NULL');
        $this->addSql('ALTER TABLE email_verifications ALTER code TYPE VARCHAR(60)');
        $this->addSql('ALTER TABLE email_verifications ALTER verified_at TYPE TIMESTAMP(6) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE email_verifications ALTER created_at TYPE TIMESTAMP(6) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE email_verifications ALTER updated_at TYPE TIMESTAMP(6) WITHOUT TIME ZONE');
        $this->addSql('COMMENT ON COLUMN email_verifications.verified_at IS NULL');
        $this->addSql('COMMENT ON COLUMN email_verifications.created_at IS NULL');
        $this->addSql('COMMENT ON COLUMN email_verifications.updated_at IS NULL');
        $this->addSql('ALTER TABLE adverts ALTER published_at TYPE TIMESTAMP(6) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE adverts ALTER created_at TYPE TIMESTAMP(6) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE adverts ALTER updated_at TYPE TIMESTAMP(6) WITHOUT TIME ZONE');
        $this->addSql('COMMENT ON COLUMN adverts.published_at IS NULL');
        $this->addSql('COMMENT ON COLUMN adverts.created_at IS NULL');
        $this->addSql('COMMENT ON COLUMN adverts.updated_at IS NULL');
        $this->addSql('ALTER TABLE cities ALTER created_at TYPE TIMESTAMP(6) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE cities ALTER updated_at TYPE TIMESTAMP(6) WITHOUT TIME ZONE');
        $this->addSql('COMMENT ON COLUMN cities.created_at IS NULL');
        $this->addSql('COMMENT ON COLUMN cities.updated_at IS NULL');
        $this->addSql('ALTER TABLE categories ALTER created_at TYPE TIMESTAMP(6) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE categories ALTER updated_at TYPE TIMESTAMP(6) WITHOUT TIME ZONE');
        $this->addSql('COMMENT ON COLUMN categories.created_at IS NULL');
        $this->addSql('COMMENT ON COLUMN categories.updated_at IS NULL');
        $this->addSql('ALTER TABLE photos ALTER created_at TYPE TIMESTAMP(6) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE photos ALTER updated_at TYPE TIMESTAMP(6) WITHOUT TIME ZONE');
        $this->addSql('COMMENT ON COLUMN photos.created_at IS NULL');
        $this->addSql('COMMENT ON COLUMN photos.updated_at IS NULL');
    }
}
