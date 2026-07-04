<?php

declare(strict_types=1);

namespace App\Database\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260704000003 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create contacts table';
    }

    public function up(Schema $schema): void
    {
        if ($schema->hasTable('contacts')) {
            return;
        }

        $t = $schema->createTable('contacts');
        $t->addColumn('id',      'integer',  ['autoincrement' => true, 'unsigned' => true, 'notnull' => true]);
        $t->addColumn('fname',   'string',   ['length' => 100, 'notnull' => true]);
        $t->addColumn('lname',   'string',   ['length' => 100, 'notnull' => true]);
        $t->addColumn('email',   'string',   ['length' => 255, 'notnull' => true]);
        $t->addColumn('msg',     'text',     ['notnull' => true]);
        $t->addColumn('status',  'smallint', ['notnull' => true, 'default' => 0]);
        $t->addColumn('created', 'datetime', ['notnull' => true]);
        $t->setPrimaryKey(['id']);
    }

    public function down(Schema $schema): void
    {
        if ($schema->hasTable('contacts')) {
            $schema->dropTable('contacts');
        }
    }
}
