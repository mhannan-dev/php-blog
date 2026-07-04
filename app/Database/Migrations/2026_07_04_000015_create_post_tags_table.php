<?php

declare(strict_types=1);

namespace App\Database\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260704000015 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create post_tags pivot table';
    }

    public function up(Schema $schema): void
    {
        if ($schema->hasTable('post_tags')) {
            return;
        }

        $t = $schema->createTable('post_tags');
        $t->addColumn('post_id', 'integer', ['unsigned' => true, 'notnull' => true]);
        $t->addColumn('tag_id',  'integer', ['unsigned' => true, 'notnull' => true]);
        
        // Composite Primary Key
        $t->setPrimaryKey(['post_id', 'tag_id']);
        
        // Indexes
        $t->addIndex(['post_id'], 'idx_post_tags_post');
        $t->addIndex(['tag_id'], 'idx_post_tags_tag');

        // Foreign Keys
        $t->addForeignKeyConstraint('posts', ['post_id'], ['id'], ['onDelete' => 'CASCADE'], 'fk_post_tags_post');
        $t->addForeignKeyConstraint('tags',  ['tag_id'],  ['id'], ['onDelete' => 'CASCADE'], 'fk_post_tags_tag');
    }

    public function down(Schema $schema): void
    {
        if ($schema->hasTable('post_tags')) {
            $schema->dropTable('post_tags');
        }
    }
}
