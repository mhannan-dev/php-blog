<?php

declare(strict_types=1);

namespace App\Database\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260704000012 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create themes table';
    }

    public function up(Schema $schema): void
    {
        if ($schema->hasTable('themes')) {
            return;
        }

        $t = $schema->createTable('themes');
        $t->addColumn('id',    'integer', ['autoincrement' => true, 'unsigned' => true, 'notnull' => true]);
        $t->addColumn('theme', 'string',  ['length' => 255, 'notnull' => true, 'default' => 'green']);
        $t->setPrimaryKey(['id']);
    }

    public function down(Schema $schema): void
    {
        if ($schema->hasTable('themes')) {
            $schema->dropTable('themes');
        }
    }
}
