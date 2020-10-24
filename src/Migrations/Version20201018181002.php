<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Class Version20200531102530
 * @package DoctrineMigrations
 */
final class Version20201018181002 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create "files" table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('
            CREATE TABLE files
            (
                id         BIGSERIAL PRIMARY KEY NOT NULL,
                folder_id  BIGINT REFERENCES folders (id) ON DELETE SET NULL,
                name       VARCHAR(255)          NOT NULL,
                extension  VARCHAR(50),
                path       VARCHAR(255)          NOT NULL,
                created_at TIMESTAMP             NOT NULL DEFAULT now(),
                updated_at  TIMESTAMP             NOT NULL DEFAULT now()
            )
        ');

        $this->addSql('ALTER TABLE files ADD COLUMN is_single BOOLEAN DEFAULT FALSE NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE files');
    }
}
