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
            INSERT INTO pages (name, slug, body, meta_title, meta_description, meta_keywords) 
            VALUES (:name, :slug, :body, :meta_title, :meta_description, :meta_keywords)
        ");

        $pages = [
            new PageDTO(
                name: 'Blog',
                slug: 'blog',
                body: '<p>Welcome to the Blog page. Discover articles on technology, programming, and more. Our writers share in-depth guides, tutorials, and insights to help you grow as a developer.</p>',
                meta_title: 'Our Blog | Nexus CMS',
                meta_description: 'Discover the latest articles on technology and programming.'
            ),
            new PageDTO(
                name: 'DMCA',
                slug: 'dmca',
                body: '<p>This DMCA page outlines our Digital Millennium Copyright Act compliance policy. If you believe your copyrighted work has been reproduced on this site in a way that constitutes copyright infringement, please contact us.</p>',
                meta_title: 'DMCA Policy'
            ),
            new PageDTO(
                name: 'Tutorial',
                slug: 'tutorial',
                body: '<p>Browse our step-by-step tutorials covering PHP, JavaScript, SQL, and more. Each tutorial is crafted to take you from beginner to intermediate in a practical, hands-on manner.</p>',
                meta_title: 'Programming Tutorials'
            ),
            new PageDTO(
                name: 'About',
                slug: 'about-us',
                body: '<p>This blog is maintained by Muhammad Hannan Ali, a passionate software developer with experience in PHP, MySQL, and modern web technologies. The goal of this site is to share knowledge with the developer community.</p>',
                meta_title: 'About Us | Nexus CMS',
                meta_description: 'Learn more about Muhammad Hannan Ali and the Nexus CMS project.'
            ),
        ];

        foreach ($pages as $dto) {
            $stmt->execute($dto->toArray());
            echo "  Seeded page: {$dto->name}\n";
        }
    }
}
