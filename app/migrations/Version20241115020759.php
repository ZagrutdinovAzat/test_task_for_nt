<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241115020759 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create tables for orders, ticket types, order tickets, and tickets';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('DROP TABLE orders');

        $this->addSql('CREATE TABLE ticket_types (
            id INT AUTO_INCREMENT NOT NULL,
            name VARCHAR(50) NOT NULL,
            price DECIMAL(10, 2) NOT NULL,
            PRIMARY KEY(id)
        )');

        $this->addSql('CREATE TABLE orders (
            id INT AUTO_INCREMENT NOT NULL,
            event_id INT NOT NULL,
            event_date DATETIME NOT NULL,
            user_id INT NOT NULL,
            created DATETIME NOT NULL,
            PRIMARY KEY(id)
        )');

        $this->addSql('CREATE TABLE order_tickets (
            id INT AUTO_INCREMENT NOT NULL,
            order_id INT NOT NULL,
            ticket_type_id INT NOT NULL,
            quantity INT NOT NULL,
            PRIMARY KEY(id),
            FOREIGN KEY (order_id) REFERENCES orders(id),
            FOREIGN KEY (ticket_type_id) REFERENCES ticket_types(id)
        )');

        $this->addSql('CREATE TABLE tickets (
            id INT AUTO_INCREMENT NOT NULL,
            order_id INT NOT NULL,
            ticket_type_id INT NOT NULL,
            barcode VARCHAR(120) NOT NULL,
            PRIMARY KEY(id),
            FOREIGN KEY (order_id) REFERENCES orders(id),
            FOREIGN KEY (ticket_type_id) REFERENCES ticket_types(id)
        )');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE tickets');

        $this->addSql('DROP TABLE order_tickets');

        $this->addSql('DROP TABLE orders');

        $this->addSql('DROP TABLE ticket_types');
    }
}
