<?php

namespace App\Database\Seeders;

use App\Database\DTOs\FooterDTO;
use App\Database\SeederInterface;
use PDO;

class FooterSeeder implements SeederInterface
{
    public function run(PDO $pdo): void
    {
        $stmt = $pdo->prepare("
            INSERT INTO footers (note) 
            VALUES (:note)
        ");

        $footers = [
            new FooterDTO(note: 'Muhammad Hannan Ali'),
        ];

        foreach ($footers as $dto) {
            $stmt->execute($dto->toArray());
            echo "  Seeded footer: {$dto->note}\n";
        }
    }
}
