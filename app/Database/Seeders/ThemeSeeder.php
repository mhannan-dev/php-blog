<?php

namespace App\Database\Seeders;

use App\Database\DTOs\ThemeDTO;
use App\Database\SeederInterface;
use PDO;

class ThemeSeeder implements SeederInterface
{
    public function run(PDO $pdo): void
    {
        $stmt = $pdo->prepare("
            INSERT INTO themes (theme) 
            VALUES (:theme)
        ");

        $themes = [
            new ThemeDTO(theme: 'green'),
        ];

        foreach ($themes as $dto) {
            $stmt->execute($dto->toArray());
            echo "  Seeded theme: {$dto->theme}\n";
        }
    }
}
