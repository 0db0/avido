<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210624081747 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE email_verifications_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE email_verifications (id INT NOT NULL, user_email VARCHAR(255) DEFAULT NULL, code VARCHAR(60) NOT NULL, verified_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_7EB1F4EE77153098 ON email_verifications (code)');
        $this->addSql('CREATE INDEX IDX_7EB1F4EE550872C ON email_verifications (user_email)');
        $this->addSql('ALTER TABLE email_verifications ADD CONSTRAINT FK_7EB1F4EE550872C FOREIGN KEY (user_email) REFERENCES users (email) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE email_verifications_id_seq CASCADE');
        $this->addSql('DROP TABLE email_verifications');
    }
}
