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
            INSERT INTO categories (name, slug) 
            VALUES (:name, :slug)
        ");

        $categories = [
            new CategoryDTO(name: 'Java', slug: 'java'),
            new CategoryDTO(name: 'SQL', slug: 'sql'),
            new CategoryDTO(name: 'Javascript', slug: 'javascript'),
            new CategoryDTO(name: 'Oracle', slug: 'oracle'),
            new CategoryDTO(name: 'Mongo DB', slug: 'mongo-db'),
            new CategoryDTO(name: 'Django', slug: 'django'),
            new CategoryDTO(name: 'PHP', slug: 'php'),
            new CategoryDTO(name: 'Python', slug: 'python'),
        ];

        foreach ($categories as $dto) {
            $stmt->execute($dto->toArray());
            echo "  Seeded category: {$dto->name}\n";
        }
    }
}
