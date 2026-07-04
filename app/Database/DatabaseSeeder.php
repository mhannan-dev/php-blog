<?php

namespace App\Database;

use PDO;
use App\Database\Seeders\CategorySeeder;
use App\Database\Seeders\ContactSeeder;
use App\Database\Seeders\FooterSeeder;
use App\Database\Seeders\MemberSeeder;
use App\Database\Seeders\PageSeeder;
use App\Database\Seeders\PostSeeder;
use App\Database\Seeders\SettingSeeder;
use App\Database\Seeders\SliderSeeder;
use App\Database\Seeders\SocialSeeder;
use App\Database\Seeders\ThemeSeeder;
use App\Database\Seeders\UserSeeder;
use App\Database\Seeders\TagSeeder;
use App\Database\Seeders\PostTagSeeder;

class DatabaseSeeder
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Orchestrate the seeding process.
     *
     * Order is important: categories and users must be seeded before posts
     * to satisfy foreign key constraints.
     */
    public function run(): void
    {
        echo "\n--- Starting Database Seeder ---\n\n";

        // 1. Disable foreign key checks to allow truncating tables with relations
        $this->pdo->exec('SET FOREIGN_KEY_CHECKS = 0');

        // 2. Clear out old data (Truncate all tables)
        $this->truncateTables([
            'categories',
            'users',
            'members',
            'posts',
            'pages',
            'sliders',
            'settings',
            'socials',
            'footers',
            'themes',
            'contacts',
            'tags',
            'post_tags',
        ]);

        // 3. Re-enable foreign key checks
        $this->pdo->exec('SET FOREIGN_KEY_CHECKS = 1');

        echo "\n";

        // 4. Run the individual seeders in dependency order
        $this->call([
            CategorySeeder::class,  // Independent — seeded first (posts reference categories)
            UserSeeder::class,      // Independent — seeded before posts
            MemberSeeder::class,    // Independent
            PostSeeder::class,      // Depends on categories & users
            PageSeeder::class,      // Independent
            SliderSeeder::class,    // Independent
            SettingSeeder::class,   // Independent
            SocialSeeder::class,    // Independent
            FooterSeeder::class,    // Independent
            ThemeSeeder::class,     // Independent
            ContactSeeder::class,   // Independent
            TagSeeder::class,       // Independent - must be before PostTagSeeder
            PostTagSeeder::class,   // Depends on posts & tags
        ]);

        echo "\n--- Database seeding completed successfully. ---\n\n";
    }

    /**
     * Truncate the specified tables.
     */
    private function truncateTables(array $tables): void
    {
        foreach ($tables as $table) {
            $this->pdo->exec("TRUNCATE TABLE `{$table}`");
            echo "  Truncated table: {$table}\n";
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
            $shortName = (new \ReflectionClass($seederClass))->getShortName();
            echo "[{$shortName}]\n";
            $seeder->run($this->pdo);
            echo "\n";
        }
    }
}
