<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201018181139 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Init DB';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql("
            create table categories
            (
                id   BIGSERIAL PRIMARY KEY,
                name VARCHAR NOT NULL
            );
        ");

        $this->addSql("
            create table products
            (
                id          BIGSERIAL PRIMARY KEY,
                name        VARCHAR NOT NULL,
                description TEXT    NOT NULL,
                price       FLOAT   NOT NULL,
                image       VARCHAR,
                category_id BIGINT  NOT NULL,
                constraint categories_id_category_id_fk
                    foreign key (category_id) references categories (id)
                        on update cascade on delete cascade
            );

        ");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
