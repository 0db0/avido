<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210623083418 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE adverts ALTER publish_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE adverts ALTER publish_at DROP DEFAULT');
        $this->addSql('ALTER TABLE adverts ALTER created_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE adverts ALTER created_at DROP DEFAULT');
        $this->addSql('ALTER TABLE adverts ALTER updated_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE adverts ALTER updated_at DROP DEFAULT');
        $this->addSql('ALTER TABLE categories ALTER created_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE categories ALTER created_at DROP DEFAULT');
        $this->addSql('ALTER TABLE categories ALTER updated_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE categories ALTER updated_at DROP DEFAULT');
        $this->addSql('ALTER TABLE cities ALTER created_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE cities ALTER created_at DROP DEFAULT');
        $this->addSql('ALTER TABLE cities ALTER updated_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE cities ALTER updated_at DROP DEFAULT');
        $this->addSql('ALTER TABLE photos ALTER created_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE photos ALTER created_at DROP DEFAULT');
        $this->addSql('ALTER TABLE photos ALTER updated_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE photos ALTER updated_at DROP DEFAULT');
        $this->addSql('ALTER TABLE regions ALTER created_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE regions ALTER created_at DROP DEFAULT');
        $this->addSql('ALTER TABLE regions ALTER updated_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE regions ALTER updated_at DROP DEFAULT');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
//        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE categories ALTER created_at TYPE TIMESTAMP(0) WITH TIME ZONE');
        $this->addSql('ALTER TABLE categories ALTER created_at DROP DEFAULT');
        $this->addSql('ALTER TABLE categories ALTER updated_at TYPE TIMESTAMP(0) WITH TIME ZONE');
        $this->addSql('ALTER TABLE categories ALTER updated_at DROP DEFAULT');
        $this->addSql('ALTER TABLE adverts ALTER publish_at TYPE TIMESTAMP(0) WITH TIME ZONE');
        $this->addSql('ALTER TABLE adverts ALTER publish_at DROP DEFAULT');
        $this->addSql('ALTER TABLE adverts ALTER created_at TYPE TIMESTAMP(0) WITH TIME ZONE');
        $this->addSql('ALTER TABLE adverts ALTER created_at DROP DEFAULT');
        $this->addSql('ALTER TABLE adverts ALTER updated_at TYPE TIMESTAMP(0) WITH TIME ZONE');
        $this->addSql('ALTER TABLE adverts ALTER updated_at DROP DEFAULT');
        $this->addSql('ALTER TABLE cities ALTER created_at TYPE TIMESTAMP(0) WITH TIME ZONE');
        $this->addSql('ALTER TABLE cities ALTER created_at DROP DEFAULT');
        $this->addSql('ALTER TABLE cities ALTER updated_at TYPE TIMESTAMP(0) WITH TIME ZONE');
        $this->addSql('ALTER TABLE cities ALTER updated_at DROP DEFAULT');
        $this->addSql('ALTER TABLE regions ALTER created_at TYPE TIMESTAMP(0) WITH TIME ZONE');
        $this->addSql('ALTER TABLE regions ALTER created_at DROP DEFAULT');
        $this->addSql('ALTER TABLE regions ALTER updated_at TYPE TIMESTAMP(0) WITH TIME ZONE');
        $this->addSql('ALTER TABLE regions ALTER updated_at DROP DEFAULT');
        $this->addSql('ALTER TABLE photos ALTER created_at TYPE TIMESTAMP(0) WITH TIME ZONE');
        $this->addSql('ALTER TABLE photos ALTER created_at DROP DEFAULT');
        $this->addSql('ALTER TABLE photos ALTER updated_at TYPE TIMESTAMP(0) WITH TIME ZONE');
        $this->addSql('ALTER TABLE photos ALTER updated_at DROP DEFAULT');
    }
}
