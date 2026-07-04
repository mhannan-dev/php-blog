<?php

declare(strict_types=1);

namespace App\Database\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class CreateFootersTable extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create footers table';
    }

    public function up(Schema $schema): void
    {
        if ($schema->hasTable('footers')) {
            return;
        }

        $t = $schema->createTable('footers');
        $t->addColumn('id',   'integer', ['autoincrement' => true, 'unsigned' => true, 'notnull' => true]);
        $t->addColumn('note', 'string',  ['length' => 100, 'notnull' => true, 'default' => '']);
        $t->setPrimaryKey(['id']);
    }

    public function down(Schema $schema): void
    {
        if ($schema->hasTable('footers')) {
            $schema->dropTable('footers');
        }
    }
}
