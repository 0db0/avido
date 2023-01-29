<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221127153153 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Populates cities & regions tables with dump.';
    }

    public function up(Schema $schema): void
    {
        $queryRegion = file_get_contents(__DIR__ . '/dump/region_dump.sql');
        $queryCities = file_get_contents(__DIR__ . '/dump/city_dump.sql');

        $this->addSql($queryRegion);
        $this->addSql($queryCities);
    }

    public function down(Schema $schema): void
    {
        $this->addSql('TRUNCATE TABLE cities RESTART IDENTITY CASCADE');
        $this->addSql('TRUNCATE TABLE regions RESTART IDENTITY CASCADE');
    }
}
