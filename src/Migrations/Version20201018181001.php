<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Class Version20200531101418
 * @package DoctrineMigrations
 */
final class Version20201018181001 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Create "folders" table';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('
            CREATE TABLE folders
            (
                id        BIGSERIAL PRIMARY KEY NOT NULL,
                parent_id BIGINT                REFERENCES folders (id) ON DELETE SET NULL,
                name      VARCHAR(255)          NOT NULL
            )
        ');
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('DROP TABLE folders');
    }
}
