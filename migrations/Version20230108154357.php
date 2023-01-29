<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230108154357 extends AbstractMigration
{
    public function getDescription(): string
    {
        return
            'Alter adverts table.
             Rename user_id to author_id.
             Makes columns city_id, category_id & author_id not null.'
        ;
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE adverts DROP CONSTRAINT fk_8c88e777a76ed395');
        $this->addSql('DROP INDEX idx_8c88e777a76ed395');
        $this->addSql('ALTER TABLE adverts ADD author_id INT NOT NULL');
        $this->addSql('ALTER TABLE adverts DROP user_id');
        $this->addSql('ALTER TABLE adverts ALTER category_id SET NOT NULL');
        $this->addSql('ALTER TABLE adverts ALTER city_id SET NOT NULL');
        $this->addSql('ALTER TABLE adverts ADD CONSTRAINT FK_8C88E777F675F31B FOREIGN KEY (author_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_8C88E777F675F31B ON adverts (author_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE adverts DROP CONSTRAINT FK_8C88E777F675F31B');
        $this->addSql('DROP INDEX IDX_8C88E777F675F31B');
        $this->addSql('ALTER TABLE adverts ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE adverts DROP author_id');
        $this->addSql('ALTER TABLE adverts ALTER category_id DROP NOT NULL');
        $this->addSql('ALTER TABLE adverts ALTER city_id DROP NOT NULL');
        $this->addSql('ALTER TABLE adverts ADD CONSTRAINT fk_8c88e777a76ed395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_8c88e777a76ed395 ON adverts (user_id)');
    }
}
