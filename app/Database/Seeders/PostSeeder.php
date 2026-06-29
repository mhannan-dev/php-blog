<?php

namespace App\Database\Seeders;

use App\Database\SeederInterface;
use PDO;

class PostSeeder implements SeederInterface
{
    public function run(PDO $pdo): void
    {
        $stmt = $pdo->prepare("
            INSERT INTO posts (cat, title, body, image, author, tags, date, userid) 
            VALUES (:cat, :title, :body, :image, :author, :tags, :date, :userid)
        ");

        $posts = [
            [
                'cat' => 1,
                'title' => 'Introduction to PHP OOP',
                'body' => '<p>Object-oriented programming (OOP) is a programming paradigm based on the concept of objects...</p>',
                'image' => 'upload/default.jpg',
                'author' => 'admin_user',
                'tags' => 'php, oop',
                'date' => date('Y-m-d H:i:s'),
                'userid' => 1
            ],
            [
                'cat' => 5,
                'title' => 'Getting Started with PDO',
                'body' => '<p>The PHP Data Objects (PDO) extension defines a lightweight, consistent interface for accessing databases...</p>',
                'image' => 'upload/default.jpg',
                'author' => 'admin_user',
                'tags' => 'php, pdo, database',
                'date' => date('Y-m-d H:i:s'),
                'userid' => 1
            ],
            [
                'cat' => 6,
                'title' => 'Building a Custom Blog',
                'body' => '<p>Building a blog from scratch can be a great way to learn a new language and framework...</p>',
                'image' => 'upload/default.jpg',
                'author' => 'jane',
                'tags' => 'blog, custom, framework',
                'date' => date('Y-m-d H:i:s'),
                'userid' => 2
            ]
        ];

        foreach ($posts as $post) {
            $stmt->execute($post);
            echo "Seeded post: {$post['title']}\n";
        }
    }
}
