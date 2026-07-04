<?php

namespace App\Database\Seeders;

use App\Database\DTOs\SliderDTO;
use App\Database\SeederInterface;
use PDO;

class SliderSeeder implements SeederInterface
{
    public function run(PDO $pdo): void
    {
        $stmt = $pdo->prepare("
            INSERT INTO sliders (title, image) 
            VALUES (:title, :image)
        ");

        $sliders = [
            new SliderDTO(title: 'Welcome to MH Blog', image: 'upload/slider1.jpg'),
            new SliderDTO(title: 'Learn PHP & MySQL',   image: 'upload/slider2.jpg'),
            new SliderDTO(title: 'Master JavaScript',   image: 'upload/slider3.jpg'),
            new SliderDTO(title: 'Explore Databases',   image: 'upload/slider4.jpg'),
        ];

        foreach ($sliders as $dto) {
            $stmt->execute($dto->toArray());
            echo "  Seeded slider: {$dto->title}\n";
        }
    }
}
