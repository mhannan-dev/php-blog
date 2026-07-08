<?php

declare(strict_types=1);

namespace App\Database\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260704000013 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create members table';
    }

    public function up(Schema $schema): void
    {
        if ($schema->hasTable('members')) {
            return;
        }

        $t = $schema->createTable('members');
        $t->addColumn('id',       'integer', ['autoincrement' => true, 'unsigned' => true, 'notnull' => true]);
        $t->addColumn('name',     'string',  ['length' => 100, 'notnull' => true, 'default' => '']);
        $t->addColumn('email',    'string',  ['length' => 255, 'notnull' => true]);
        $t->addColumn('username', 'string',  ['length' => 255, 'notnull' => true]);
        $t->addColumn('password', 'string',  ['length' => 255, 'notnull' => true]); // bcrypt-ready
        $t->setPrimaryKey(['id']);
    }

    public function down(Schema $schema): void
    {
        if ($schema->hasTable('members')) {
            $schema->dropTable('members');
        }
    }
}
