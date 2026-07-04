<?php

declare(strict_types=1);

namespace App\Database\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class CreateUsersTable extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create users table';
    }

    public function up(Schema $schema): void
    {
        if ($schema->hasTable('users')) {
            return;
        }

        $t = $schema->createTable('users');
        $t->addColumn('id',       'integer', ['autoincrement' => true, 'unsigned' => true, 'notnull' => true]);
        $t->addColumn('name',     'string',  ['length' => 100, 'notnull' => true, 'default' => '']);
        $t->addColumn('username', 'string',  ['length' => 50,  'notnull' => true]);
        $t->addColumn('email',    'string',  ['length' => 255, 'notnull' => true]);
        $t->addColumn('password', 'string',  ['length' => 255, 'notnull' => true]); // bcrypt-ready
        $t->addColumn('details',  'text',    ['notnull' => false, 'default' => null]);
        $t->addColumn('role',     'integer', ['notnull' => true, 'default' => 2]);   // 0=admin, 1=author, 2=subscriber
        $t->addColumn('userid',   'integer', ['notnull' => true, 'default' => 0]);
        $t->setPrimaryKey(['id']);
        $t->addUniqueIndex(['username'], 'uq_users_username');
        $t->addUniqueIndex(['email'],    'uq_users_email');
    }

    public function down(Schema $schema): void
    {
        if ($schema->hasTable('users')) {
            $schema->dropTable('users');
        }
    }
}
