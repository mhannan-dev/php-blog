<?php

namespace App\Database\Seeders;

use App\Database\DTOs\SettingDTO;
use App\Database\SeederInterface;
use PDO;

class SettingSeeder implements SeederInterface
{
    public function run(PDO $pdo): void
    {
        $stmt = $pdo->prepare("
            INSERT INTO settings (logo, title, slogan)
            VALUES (:logo, :title, :slogan)
        ");

        $settings = [
            new SettingDTO(
                logo:   'upload/logo.png',
                title:  'MH Blog',
                slogan: 'Write for knowledge'
            ),
        ];

        foreach ($settings as $dto) {
            $stmt->execute($dto->toArray());
            echo "  Seeded setting: {$dto->title}\n";
        }
    }
}
