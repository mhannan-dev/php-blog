<?php

declare(strict_types=1);

namespace App\Database\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260704000005 extends AbstractMigration
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
        $t->addColumn('name',             'string',  ['length' => 50,  'notnull' => true]);
        $t->addColumn('slug',             'string',  ['length' => 100, 'notnull' => true]);
        $t->addColumn('body',             'text',    ['notnull' => true]);
        $t->addColumn('meta_title',       'string',  ['length' => 100, 'notnull' => true, 'default' => '']);
        $t->addColumn('meta_description', 'string',  ['length' => 255, 'notnull' => true, 'default' => '']);
        $t->addColumn('meta_keywords',    'string',  ['length' => 255, 'notnull' => true, 'default' => '']);
        $t->setPrimaryKey(['id']);
        $t->addUniqueIndex(['name'], 'uq_pages_name');
        $t->addUniqueIndex(['slug'], 'uq_pages_slug');
    }

    public function down(Schema $schema): void
    {
        if ($schema->hasTable('pages')) {
            $schema->dropTable('pages');
        }
    }
}
