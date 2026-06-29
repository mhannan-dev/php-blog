<?php

namespace App\Database;

use PDO;
use App\Database\Seeders\UserSeeder;
use App\Database\Seeders\PostSeeder;

class DatabaseSeeder
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Orchestrate the seeding process.
     */
    public function run(): void
    {
        // 1. Disable foreign key checks to allow truncating tables with relations
        $this->pdo->exec('SET FOREIGN_KEY_CHECKS = 0');

        // 2. Clear out old data (Truncate)
        $this->truncateTables([
            'posts',
            'users'
        ]);

        // 3. Re-enable foreign key checks
        $this->pdo->exec('SET FOREIGN_KEY_CHECKS = 1');

        // 4. Run the individual seeders
        $this->call([
            UserSeeder::class,
            PostSeeder::class,
        ]);
        
        echo "Database seeding completed successfully.\n";
    }

    /**
     * Truncate the specified tables.
     */
    private function truncateTables(array $tables): void
    {
        foreach ($tables as $table) {
            $this->pdo->exec("TRUNCATE TABLE `{$table}`");
            echo "Truncated table: {$table}\n";
        }
    }

    /**
     * Instantiate and run the given seeder classes.
     */
    private function call(array $seeders): void
    {
        foreach ($seeders as $seederClass) {
            /** @var SeederInterface $seeder */
            $seeder = new $seederClass();
            echo "Running seeder: {$seederClass}\n";
            $seeder->run($this->pdo);
        }
    }
}
