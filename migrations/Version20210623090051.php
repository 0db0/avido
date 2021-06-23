<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210623090051 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(file_get_contents(__DIR__ . '/dump/region_dump.sql'));
        $this->addSql(file_get_contents(__DIR__ . '/dump/city_dump.sql'));
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DELETE FROM cities');
        $this->addSql('DELETE FROM regions');
    }
}
