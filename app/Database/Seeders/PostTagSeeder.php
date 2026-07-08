<?php

namespace App\Database\Seeders;

use App\Database\DTOs\PostTagDTO;
use App\Database\SeederInterface;
use PDO;

class PostTagSeeder implements SeederInterface
{
    public function run(PDO $pdo): void
    {
        $stmt = $pdo->prepare("
            INSERT INTO post_tags (post_id, tag_id) 
            VALUES (:post_id, :tag_id)
        ");

        // Assuming IDs from arrays match insertion order exactly for seeders:
        // Post 1 (Intro to PHP OOP) -> Tags: PHP (1), OOP (2)
        // Post 2 (PDO) -> Tags: PHP (1), PDO (3), Database (4)
        // Post 3 (Custom Blog) -> Tags: Blog (5), Custom (6), PHP (1)
        // Post 4 (SQL Joins) -> Tags: SQL (7), Database (4), Joins (8)
        // Post 5 (JS ES6) -> Tags: JavaScript (9), ES6 (10), Web (11)

        $relations = [
            new PostTagDTO(post_id: 1, tag_id: 1),
            new PostTagDTO(post_id: 1, tag_id: 2),

            new PostTagDTO(post_id: 2, tag_id: 1),
            new PostTagDTO(post_id: 2, tag_id: 3),
            new PostTagDTO(post_id: 2, tag_id: 4),

            new PostTagDTO(post_id: 3, tag_id: 5),
            new PostTagDTO(post_id: 3, tag_id: 6),
            new PostTagDTO(post_id: 3, tag_id: 1),

            new PostTagDTO(post_id: 4, tag_id: 7),
            new PostTagDTO(post_id: 4, tag_id: 4),
            new PostTagDTO(post_id: 4, tag_id: 8),

            new PostTagDTO(post_id: 5, tag_id: 9),
            new PostTagDTO(post_id: 5, tag_id: 10),
            new PostTagDTO(post_id: 5, tag_id: 11),
        ];

        foreach ($relations as $dto) {
            $stmt->execute($dto->toArray());
            echo "  Seeded post_tag relation: post_id {$dto->post_id} -> tag_id {$dto->tag_id}\n";
        }
    }
}
