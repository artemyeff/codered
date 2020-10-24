<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201023220652 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql("
            create table orders
            (
                id         BIGSERIAL PRIMARY KEY,
                sum        FLOAT     NOT NULL,
                phone      VARCHAR   NOT NULL,
                count      INT       NOT NULL,
                created_at TIMESTAMP NOT NULL DEFAULT now(),
                updated_at TIMESTAMP NOT NULL DEFAULT now()
            );
        ");

        $this->addSql("     
            create table orders_products
            (
                id         BIGSERIAL PRIMARY KEY,
                product_id BIGINT NOT NULL,
                order_id   BIGINT NOT NULL,
                count      INT    NOT NULL,
                sum        FLOAT  NOT NULL,
                constraint products_id_product_id_fk
                    foreign key (product_id) references products (id)
                        on update cascade on delete cascade,
                constraint orders_id_order_id_fk
                    foreign key (order_id) references orders (id)
                        on update cascade on delete cascade
            );
        ");

        $this->addSql("
            create unique index order_product__unique__idx ON orders_products (product_id, order_id);
        ");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
