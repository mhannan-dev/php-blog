<?php

namespace App\Database\Seeders;

use App\Database\DTOs\PageDTO;
use App\Database\SeederInterface;
use PDO;

class PageSeeder implements SeederInterface
{
    public function run(PDO $pdo): void
    {
        $stmt = $pdo->prepare("
            INSERT INTO pages (name, body) 
            VALUES (:name, :body)
        ");

        $pages = [
            new PageDTO(
                name: 'Blog',
                body: '<p>Welcome to the Blog page. Discover articles on technology, programming, and more. Our writers share in-depth guides, tutorials, and insights to help you grow as a developer.</p>'
            ),
            new PageDTO(
                name: 'DMCA',
                body: '<p>This DMCA page outlines our Digital Millennium Copyright Act compliance policy. If you believe your copyrighted work has been reproduced on this site in a way that constitutes copyright infringement, please contact us.</p>'
            ),
            new PageDTO(
                name: 'Tutorial',
                body: '<p>Browse our step-by-step tutorials covering PHP, JavaScript, SQL, and more. Each tutorial is crafted to take you from beginner to intermediate in a practical, hands-on manner.</p>'
            ),
            new PageDTO(
                name: 'About',
                body: '<p>This blog is maintained by Muhammad Hannan Ali, a passionate software developer with experience in PHP, MySQL, and modern web technologies. The goal of this site is to share knowledge with the developer community.</p>'
            ),
        ];

        foreach ($pages as $dto) {
            $stmt->execute($dto->toArray());
            echo "  Seeded page: {$dto->name}\n";
        }
    }
}
