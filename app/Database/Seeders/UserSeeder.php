<?php

namespace App\Database\Seeders;

use App\Database\DTOs\UserDTO;
use App\Database\SeederInterface;
use PDO;

class UserSeeder implements SeederInterface
{
    public function run(PDO $pdo): void
    {
        $stmt = $pdo->prepare("
            INSERT INTO users (name, username, email, password, details, role, userid) 
            VALUES (:name, :username, :email, :password, :details, :role, :userid)
        ");

        $users = [
            new UserDTO(
                name:     'Muhammad Hannan Ali',
                username: 'admin',
                email:    'mdhannan.info@gmail.com',
                password: password_hash('Test@1234', PASSWORD_BCRYPT),
                details:  '<p>MD. HANNAN ALI</p>',
                role:     0,
                userid:   0
            ),
            new UserDTO(
                name:     'Mr. Author',
                username: 'author',
                email:    'author@example.com',
                password: password_hash('Test@1234', PASSWORD_BCRYPT),
                details:  '<p>Author bio</p>',
                role:     1,
                userid:   0
            ),
            new UserDTO(
                name:     'Tuhin',
                username: 'tuhin',
                email:    'tuhin@example.com',
                password: password_hash('Test@1234', PASSWORD_BCRYPT),
                details:  '',
                role:     2,
                userid:   0
            ),
        ];

        foreach ($users as $dto) {
            $stmt->execute($dto->toArray());
            echo "  Seeded user: {$dto->email}\n";
        }
    }
}
