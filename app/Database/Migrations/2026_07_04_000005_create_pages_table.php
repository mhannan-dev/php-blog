<?php

declare(strict_types=1);

namespace App\Database\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class CreatePagesTable extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create pages table';
    }

    public function up(Schema $schema): void
    {
        if ($schema->hasTable('pages')) {
            return;
        }

        $t = $schema->createTable('pages');
        $t->addColumn('id',   'integer', ['autoincrement' => true, 'unsigned' => true, 'notnull' => true]);
        $t->addColumn('name', 'string',  ['length' => 50,  'notnull' => true]);
        $t->addColumn('body', 'text',    ['notnull' => true]);
        $t->setPrimaryKey(['id']);
        $t->addUniqueIndex(['name'], 'uq_pages_name');
    }

    public function down(Schema $schema): void
    {
        if ($schema->hasTable('pages')) {
            $schema->dropTable('pages');
        }
    }
}
