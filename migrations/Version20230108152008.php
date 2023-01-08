<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230108152008 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Alter adverts table rename column publish_at';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE adverts RENAME COLUMN publish_at TO published_at');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE adverts RENAME COLUMN published_at TO publish_at');
    }
}
