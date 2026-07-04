<?php

declare(strict_types=1);

namespace App\Database\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class CreateSlidersTable extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create sliders table';
    }

    public function up(Schema $schema): void
    {
        if ($schema->hasTable('sliders')) {
            return;
        }

        $t = $schema->createTable('sliders');
        $t->addColumn('id',        'integer',  ['autoincrement' => true, 'unsigned' => true, 'notnull' => true]);
        $t->addColumn('title',     'string',   ['length' => 100, 'notnull' => true, 'default' => '']);
        $t->addColumn('image',     'string',   ['length' => 100, 'notnull' => true, 'default' => '']);
        $t->addColumn('timestamp', 'datetime', ['notnull' => true, 'default' => 'CURRENT_TIMESTAMP']);
        $t->setPrimaryKey(['id']);
    }

    public function down(Schema $schema): void
    {
        if ($schema->hasTable('sliders')) {
            $schema->dropTable('sliders');
        }
    }
}
