<?php

declare(strict_types=1);

namespace App\Database\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * posts depends on categories (cat FK) and users (userid FK).
 * Run AFTER CreateCategoriesTable and CreateUsersTable.
 */
final class CreatePostsTable extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create posts table';
    }

    public function up(Schema $schema): void
    {
        if ($schema->hasTable('posts')) {
            return;
        }

        $t = $schema->createTable('posts');
        $t->addColumn('id',     'integer',  ['autoincrement' => true, 'unsigned' => true, 'notnull' => true]);
        $t->addColumn('cat',    'integer',  ['unsigned' => true, 'notnull' => true, 'default' => 0]);
        $t->addColumn('title',  'string',   ['length' => 100, 'notnull' => true]);
        $t->addColumn('body',   'text',     ['notnull' => true]);
        $t->addColumn('image',  'string',   ['length' => 255, 'notnull' => true, 'default' => '']);
        $t->addColumn('author', 'string',   ['length' => 50,  'notnull' => true, 'default' => '']);
        $t->addColumn('tags',   'string',   ['length' => 255, 'notnull' => true, 'default' => '']);
        $t->addColumn('date',   'datetime', ['notnull' => true, 'default' => 'CURRENT_TIMESTAMP']);
        $t->addColumn('userid', 'integer',  ['unsigned' => true, 'notnull' => true, 'default' => 0]);
        $t->setPrimaryKey(['id']);
        $t->addUniqueIndex(['title'], 'uq_posts_title');
    }

    public function down(Schema $schema): void
    {
        if ($schema->hasTable('posts')) {
            $schema->dropTable('posts');
        }
    }
}
