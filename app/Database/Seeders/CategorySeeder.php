<?php

namespace App\Database\Seeders;

use App\Database\DTOs\CategoryDTO;
use App\Database\SeederInterface;
use PDO;

class CategorySeeder implements SeederInterface
{
    public function run(PDO $pdo): void
    {
        $stmt = $pdo->prepare("
            INSERT INTO categories (name) 
            VALUES (:name)
        ");

        $categories = [
            new CategoryDTO(name: 'Java'),
            new CategoryDTO(name: 'SQL'),
            new CategoryDTO(name: 'Javascript'),
            new CategoryDTO(name: 'Oracle'),
            new CategoryDTO(name: 'Mongo DB'),
            new CategoryDTO(name: 'Django'),
            new CategoryDTO(name: 'PHP'),
            new CategoryDTO(name: 'Python'),
        ];

        foreach ($categories as $dto) {
            $stmt->execute($dto->toArray());
            echo "  Seeded category: {$dto->name}\n";
        }
    }
}
