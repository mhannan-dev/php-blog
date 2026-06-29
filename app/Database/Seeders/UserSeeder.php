<?php

namespace App\Database\Seeders;

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
            [
                'name' => 'Admin User',
                'username' => 'admin_user',
                'email' => 'admin@example.com',
                'password' => md5('password123'), // Legacy system seems to use md5 based on the seed data 81dc9bdb52d04dc20036dbd8313ed055
                'details' => '<p>Admin bio</p>',
                'role' => 0,
                'userid' => 0
            ],
            [
                'name' => 'Jane Doe',
                'username' => 'jane',
                'email' => 'jane@example.com',
                'password' => md5('password123'),
                'details' => '<p>Jane bio</p>',
                'role' => 1,
                'userid' => 0
            ]
        ];

        foreach ($users as $user) {
            $stmt->execute($user);
            echo "Seeded user: {$user['email']}\n";
        }
    }
}
