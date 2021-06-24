<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210624100624 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE email_verifications ALTER user_email SET NOT NULL');
        $this->addSql('ALTER TABLE email_verifications ADD CONSTRAINT FK_7EB1F4EE550872C FOREIGN KEY (user_email) REFERENCES users (email) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE email_verifications DROP CONSTRAINT FK_7EB1F4EE550872C');
        $this->addSql('ALTER TABLE email_verifications ALTER user_email DROP NOT NULL');
    }
}
