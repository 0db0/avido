<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210623114944 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'for dev purpose';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('DELETE FROM categories');
        $this->addSql('ALTER sequence categories_id_seq restart');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
