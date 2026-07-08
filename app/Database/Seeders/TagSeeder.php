<?php

namespace App\Database\Seeders;

use App\Database\DTOs\TagDTO;
use App\Database\SeederInterface;
use PDO;

class TagSeeder implements SeederInterface
{
    public function run(PDO $pdo): void
    {
        $stmt = $pdo->prepare("
            INSERT INTO tags (name, slug) 
            VALUES (:name, :slug)
        ");

        $tags = [
            new TagDTO(name: 'PHP', slug: 'php'),
            new TagDTO(name: 'OOP', slug: 'oop'),
            new TagDTO(name: 'PDO', slug: 'pdo'),
            new TagDTO(name: 'Database', slug: 'database'),
            new TagDTO(name: 'Shopware', slug: 'shopware'),
            new TagDTO(name: 'SQL', slug: 'sql'),
            new TagDTO(name: 'Shopify', slug: 'shopify'),
        ];

        foreach ($tags as $dto) {
            $stmt->execute($dto->toArray());
            echo "  Seeded tag: {$dto->name}\n";
        }
    }
}
