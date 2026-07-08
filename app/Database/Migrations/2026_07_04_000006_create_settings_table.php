<?php

declare(strict_types=1);

namespace App\Database\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260704000006 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create settings table — stores site logo, title and slogan';
    }

    public function up(Schema $schema): void
    {
        if ($schema->hasTable('settings')) {
            return;
        }

        $t = $schema->createTable('settings');
        $t->addColumn('id',     'integer', ['autoincrement' => true, 'unsigned' => true, 'notnull' => true]);
        $t->addColumn('logo',   'string',  ['length' => 255, 'notnull' => true, 'default' => '']);
        $t->addColumn('title',  'string',  ['length' => 100, 'notnull' => true, 'default' => '']);
        $t->addColumn('slogan', 'string',  ['length' => 100, 'notnull' => true, 'default' => '']);
        $t->setPrimaryKey(['id']);
    }

    public function down(Schema $schema): void
    {
        if ($schema->hasTable('settings')) {
            $schema->dropTable('settings');
        }
    }
}
