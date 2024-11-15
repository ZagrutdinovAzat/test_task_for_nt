<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241114210310 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create orders table';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $table = $schema->createTable('orders');
        $table->addColumn('id', 'integer', ['autoincrement' => true, 'notnull' => true]);
        $table->addColumn('event_id', 'integer', ['notnull' => false]);
        $table->addColumn('event_date', 'datetime', ['notnull' => false]);
        $table->addColumn('ticket_adult_price', 'integer', ['notnull' => false]);
        $table->addColumn('ticket_adult_quantity', 'integer', ['notnull' => false]);
        $table->addColumn('ticket_kid_price', 'integer', ['notnull' => false]);
        $table->addColumn('ticket_kid_quantity', 'integer', ['notnull' => false]);
        $table->addColumn('barcode', 'string', ['length' => 120, 'notnull' => false]);
        $table->addColumn('user_id', 'integer', ['notnull' => false]);
        $table->addColumn('equal_price', 'integer', ['notnull' => false]);
        $table->addColumn('created', 'datetime', ['notnull' => false]);
        $table->setPrimaryKey(['id']);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $schema->dropTable('orders');
    }
}
