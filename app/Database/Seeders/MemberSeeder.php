<?php

namespace App\Database\Seeders;

use App\Database\DTOs\MemberDTO;
use App\Database\SeederInterface;
use PDO;

class MemberSeeder implements SeederInterface
{
    public function run(PDO $pdo): void
    {
        $stmt = $pdo->prepare("
            INSERT INTO members (name, email, username, password)
            VALUES (:name, :email, :username, :password)
        ");

        $members = [
            new MemberDTO(
                name:     'MD. HANNAN ALI',
                email:    'hannan@arobil.com',
                username: 'admin',
                password: md5('123456')
            ),
            new MemberDTO(
                name:     'Mahmudul Hasan',
                email:    'hasan@example.com',
                username: 'hasan',
                password: md5('123456')
            ),
            new MemberDTO(
                name:     'Shahjalal',
                email:    'jalal@example.com',
                username: 'jalal',
                password: md5('123456')
            ),
            new MemberDTO(
                name:     'Abdul Mannan',
                email:    'amannan@example.com',
                username: 'amannan',
                password: md5('123456')
            ),
            new MemberDTO(
                name:     'Abdus Salam',
                email:    'asalam@example.com',
                username: 'asalam',
                password: md5('123456')
            ),
            new MemberDTO(
                name:     'Saddam Hossain Arif',
                email:    'saddam@example.com',
                username: 'saddam',
                password: md5('123456')
            ),
            new MemberDTO(
                name:     'Abdul Bari',
                email:    'bari@example.com',
                username: 'abari',
                password: md5('123456')
            ),
        ];

        foreach ($members as $dto) {
            $stmt->execute($dto->toArray());
            echo "  Seeded member: {$dto->username}\n";
        }
    }
}
