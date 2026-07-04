<?php

namespace App\Database\Seeders;

use App\Database\DTOs\PostDTO;
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
            new PostDTO(
                cat:    1,
                title:  'Introduction to PHP OOP',
                body:   '<p>Object-oriented programming (OOP) is a programming paradigm based on the concept of objects. It allows developers to structure complex programs into reusable, modular pieces of code.</p>',
                image:  'upload/default.jpg',
                author: 'admin',
                tags:   'php, oop',
                date:   date('Y-m-d H:i:s'),
                userid: 1
            ),
            new PostDTO(
                cat:    5,
                title:  'Getting Started with PDO',
                body:   '<p>The PHP Data Objects (PDO) extension defines a lightweight, consistent interface for accessing databases in PHP. It provides a data-access abstraction layer for working with various database drivers.</p>',
                image:  'upload/default.jpg',
                author: 'admin',
                tags:   'php, pdo, database',
                date:   date('Y-m-d H:i:s'),
                userid: 1
            ),
            new PostDTO(
                cat:    6,
                title:  'Building a Custom PHP Blog',
                body:   '<p>Building a blog from scratch is a great way to understand how web applications are structured. In this post we cover routing, templating, and database interactions without any framework overhead.</p>',
                image:  'upload/default.jpg',
                author: 'author',
                tags:   'blog, custom, php',
                date:   date('Y-m-d H:i:s'),
                userid: 2
            ),
            new PostDTO(
                cat:    5,
                title:  'Understanding SQL Joins',
                body:   '<p>SQL JOINs allow you to combine rows from two or more tables based on a related column. This post covers INNER JOIN, LEFT JOIN, RIGHT JOIN, and FULL OUTER JOIN with practical examples.</p>',
                image:  'upload/default.jpg',
                author: 'admin',
                tags:   'sql, database, joins',
                date:   date('Y-m-d H:i:s'),
                userid: 1
            ),
            new PostDTO(
                cat:    6,
                title:  'JavaScript ES6 Features',
                body:   '<p>ES6 (ECMAScript 2015) introduced many powerful features to JavaScript including arrow functions, template literals, destructuring, and the let/const keywords. This post walks through the most impactful additions.</p>',
                image:  'upload/default.jpg',
                author: 'author',
                tags:   'javascript, es6, web',
                date:   date('Y-m-d H:i:s'),
                userid: 2
            ),
        ];

        foreach ($posts as $dto) {
            $stmt->execute($dto->toArray());
            echo "  Seeded post: {$dto->title}\n";
        }
    }
}
