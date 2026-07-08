<?php

declare(strict_types=1);

namespace App\Database\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260704000014 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create tags table';
    }

    public function up(Schema $schema): void
    {
        if ($schema->hasTable('tags')) {
            return;
        }

        $t = $schema->createTable('tags');
        $t->addColumn('id',   'integer', ['autoincrement' => true, 'unsigned' => true, 'notnull' => true]);
        $t->addColumn('name', 'string',  ['length' => 100, 'notnull' => true]);
        $t->addColumn('slug', 'string',  ['length' => 100, 'notnull' => true]);
        
        $t->setPrimaryKey(['id']);
        $t->addUniqueIndex(['name'], 'uq_tags_name');
        $t->addUniqueIndex(['slug'], 'uq_tags_slug');
    }

    public function down(Schema $schema): void
    {
        if ($schema->hasTable('tags')) {
            $schema->dropTable('tags');
        }
    }
}
