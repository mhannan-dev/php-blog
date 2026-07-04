<?php

namespace App\Database\Seeders;

use App\Database\DTOs\SocialDTO;
use App\Database\SeederInterface;
use PDO;

class SocialSeeder implements SeederInterface
{
    public function run(PDO $pdo): void
    {
        $stmt = $pdo->prepare("
            INSERT INTO socials (fb, tw, ln) 
            VALUES (:fb, :tw, :ln)
        ");

        $socials = [
            new SocialDTO(
                fb: 'https://www.facebook.com/muhammadhannanali',
                tw: 'https://github.com/MyCodeBin',
                ln: 'https://www.linkedin.com/in/muhammad-hannan-87abb948/'
            ),
        ];

        foreach ($socials as $dto) {
            $stmt->execute($dto->toArray());
            echo "  Seeded social links\n";
        }
    }
}
