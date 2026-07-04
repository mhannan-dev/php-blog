<?php

declare(strict_types=1);

namespace App\Database\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class CreateSocialsTable extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create socials table';
    }

    public function up(Schema $schema): void
    {
        if ($schema->hasTable('socials')) {
            return;
        }

        $t = $schema->createTable('socials');
        $t->addColumn('id', 'integer', ['autoincrement' => true, 'unsigned' => true, 'notnull' => true]);
        $t->addColumn('fb', 'string',  ['length' => 100, 'notnull' => true, 'default' => '']);
        $t->addColumn('tw', 'string',  ['length' => 100, 'notnull' => true, 'default' => '']);
        $t->addColumn('ln', 'string',  ['length' => 100, 'notnull' => true, 'default' => '']);
        $t->setPrimaryKey(['id']);
    }

    public function down(Schema $schema): void
    {
        if ($schema->hasTable('socials')) {
            $schema->dropTable('socials');
        }
    }
}
