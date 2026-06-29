<?php

namespace App\Database;

use PDO;

interface SeederInterface
{
    /**
     * Run the database seeds.
     *
     * @param PDO $pdo The PDO connection instance.
     * @return void
     */
    public function run(PDO $pdo): void;
}
