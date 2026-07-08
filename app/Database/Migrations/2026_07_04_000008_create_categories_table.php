<?php

declare(strict_types=1);

namespace App\Database\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260704000008 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create categories table';
    }

    public function up(Schema $schema): void
    {
        if ($schema->hasTable('categories')) {
            return;
        }

        $t = $schema->createTable('categories');
        $t->addColumn('id',   'integer', ['autoincrement' => true, 'unsigned' => true, 'notnull' => true]);
        $t->addColumn('name', 'string',  ['length' => 100, 'notnull' => true]);
        $t->addColumn('slug', 'string',  ['length' => 100, 'notnull' => true]);
        $t->setPrimaryKey(['id']);
        $t->addUniqueIndex(['slug'], 'uq_categories_slug');
    }

    public function down(Schema $schema): void
    {
        if ($schema->hasTable('categories')) {
            $schema->dropTable('categories');
        }
    }
}
