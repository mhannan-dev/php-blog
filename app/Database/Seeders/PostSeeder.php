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
            INSERT INTO posts (category_id, title, slug, body, image, author, date, user_id, meta_title, meta_description, meta_keywords) 
            VALUES (:category_id, :title, :slug, :body, :image, :author, :date, :user_id, :meta_title, :meta_description, :meta_keywords)
        ");

        $posts = [
            new PostDTO(
                category_id: 1,
                title:       'Introduction to PHP OOP',
                slug:        'introduction-to-php-oop',
                body:        '<p>Object-oriented programming (OOP) is a programming paradigm based on the concept of objects. It allows developers to structure complex programs into reusable, modular pieces of code.</p>',
                image:       'upload/default.jpg',
                author:      'admin',
                date:        date('Y-m-d H:i:s'),
                user_id:     1,
                meta_title:  'Introduction to PHP OOP Guide',
                meta_description: 'Learn the fundamentals of Object-oriented programming in PHP.',
                meta_keywords: 'php, oop, programming'
            ),
            new PostDTO(
                category_id: 5,
                title:       'Getting Started with PDO',
                slug:        'getting-started-with-pdo',
                body:        '<p>The PHP Data Objects (PDO) extension defines a lightweight, consistent interface for accessing databases in PHP. It provides a data-access abstraction layer for working with various database drivers.</p>',
                image:       'upload/default.jpg',
                author:      'admin',
                date:        date('Y-m-d H:i:s'),
                user_id:     1,
                meta_title:  'Getting Started with PDO Database Extension',
                meta_keywords: 'php, pdo, database'
            ),
            new PostDTO(
                category_id: 6,
                title:       'Building a Custom PHP Blog',
                slug:        'building-a-custom-php-blog',
                body:        '<p>Building a blog from scratch is a great way to understand how web applications are structured. In this post we cover routing, templating, and database interactions without any framework overhead.</p>',
                image:       'upload/default.jpg',
                author:      'author',
                date:        date('Y-m-d H:i:s'),
                user_id:     2,
                meta_keywords: 'blog, custom, php'
            ),
            new PostDTO(
                category_id: 5,
                title:       'Understanding SQL Joins',
                slug:        'understanding-sql-joins',
                body:        '<p>SQL JOINs allow you to combine rows from two or more tables based on a related column. This post covers INNER JOIN, LEFT JOIN, RIGHT JOIN, and FULL OUTER JOIN with practical examples.</p>',
                image:       'upload/default.jpg',
                author:      'admin',
                date:        date('Y-m-d H:i:s'),
                user_id:     1,
                meta_keywords: 'sql, database, joins'
            ),
            new PostDTO(
                category_id: 6,
                title:       'JavaScript ES6 Features',
                slug:        'javascript-es6-features',
                body:        '<p>ES6 (ECMAScript 2015) introduced many powerful features to JavaScript including arrow functions, template literals, destructuring, and the let/const keywords. This post walks through the most impactful additions.</p>',
                image:       'upload/default.jpg',
                author:      'author',
                date:        date('Y-m-d H:i:s'),
                user_id:     2,
                meta_keywords: 'javascript, es6, web'
            ),
        ];

        foreach ($posts as $dto) {
            $stmt->execute($dto->toArray());
            echo "  Seeded post: {$dto->title}\n";
        }
    }
}
