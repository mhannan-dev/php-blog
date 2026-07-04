<?php

/**
 * migrations.php — Pure Doctrine Migrations config array.
 * No side-effects — no autoloader, no dotenv loading here.
 * All environment setup is done by bin/migrate before this file is required.
 */

declare(strict_types=1);

return [
    'table_storage' => [
        'table_name'                 => 'doctrine_migration_versions',
        'version_column_name'        => 'version',
        'version_column_length'      => 191,
        'executed_at_column_name'    => 'executed_at',
        'execution_time_column_name' => 'execution_time',
    ],

    'migrations_paths' => [
        'App\Database\Migrations' => __DIR__ . '/app/Database/Migrations',
    ],

    'all_or_nothing'          => true,
    'transactional'           => true,
    'check_database_platform' => true,
    'organize_migrations'     => 'none',
];
