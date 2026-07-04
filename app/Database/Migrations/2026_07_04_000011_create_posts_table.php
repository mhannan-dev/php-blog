<?php

declare(strict_types=1);

namespace App\Database\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * posts depends on categories (cat FK) and users (userid FK).
 * Run AFTER CreateCategoriesTable and CreateUsersTable.
 */
final class Version20260704000011 extends AbstractMigration
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
        $t->addColumn('id',               'integer',  ['autoincrement' => true, 'unsigned' => true, 'notnull' => true]);
        $t->addColumn('category_id',      'integer',  ['unsigned' => true, 'notnull' => true, 'default' => 0]);
        $t->addColumn('user_id',          'integer',  ['unsigned' => true, 'notnull' => true, 'default' => 0]);
        $t->addColumn('title',            'string',   ['length' => 100, 'notnull' => true]);
        $t->addColumn('slug',             'string',   ['length' => 150, 'notnull' => true]);
        $t->addColumn('body',             'text',     ['notnull' => true]);
        $t->addColumn('image',            'string',   ['length' => 255, 'notnull' => true, 'default' => '']);
        $t->addColumn('author',           'string',   ['length' => 50,  'notnull' => true, 'default' => '']);
        $t->addColumn('date',             'datetime', ['notnull' => true, 'default' => 'CURRENT_TIMESTAMP']);
        $t->addColumn('meta_title',       'string',   ['length' => 100, 'notnull' => true, 'default' => '']);
        $t->addColumn('meta_description', 'string',   ['length' => 255, 'notnull' => true, 'default' => '']);
        $t->addColumn('meta_keywords',    'string',   ['length' => 255, 'notnull' => true, 'default' => '']);

        $t->setPrimaryKey(['id']);
        
        // Indexes
        $t->addUniqueIndex(['title'], 'uq_posts_title');
        $t->addUniqueIndex(['slug'], 'uq_posts_slug');
        $t->addIndex(['category_id'], 'idx_posts_category');
        $t->addIndex(['user_id'], 'idx_posts_user');
        $t->addIndex(['date'], 'idx_posts_date');

        // Foreign Keys
        $t->addForeignKeyConstraint('categories', ['category_id'], ['id'], ['onDelete' => 'CASCADE'], 'fk_posts_category');
        $t->addForeignKeyConstraint('users', ['user_id'], ['id'], ['onDelete' => 'CASCADE'], 'fk_posts_user');
    }

    public function down(Schema $schema): void
    {
        if ($schema->hasTable('posts')) {
            $schema->dropTable('posts');
        }
    }
}
