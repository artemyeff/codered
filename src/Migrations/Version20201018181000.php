<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201018181000 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '"users"';
    }

    public function up(Schema $schema) : void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE "users" (id BIGSERIAL PRIMARY KEY, username VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE UNIQUE INDEX users_username_unique_idx ON "users" (username)');

        $this->addSql('ALTER TABLE users ADD COLUMN first_name VARCHAR(100) NOT NULL;');
        $this->addSql('ALTER TABLE users ADD COLUMN patronymic VARCHAR(100);');
        $this->addSql('ALTER TABLE users ADD COLUMN last_name VARCHAR(100) NOT NULL;');
        $this->addSql('ALTER TABLE users RENAME COLUMN username TO login;');
        $this->addSql('ALTER TABLE users ALTER COLUMN login DROP NOT NULL;');
    }

    public function down(Schema $schema) : void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP TABLE "users"');
    }
}
