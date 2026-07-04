<?php

namespace App\Database\Seeders;

use App\Database\DTOs\ContactDTO;
use App\Database\SeederInterface;
use PDO;

class ContactSeeder implements SeederInterface
{
    public function run(PDO $pdo): void
    {
        $stmt = $pdo->prepare("
            INSERT INTO contacts (fname, lname, email, msg, status, created)
            VALUES (:fname, :lname, :email, :msg, :status, :created)
        ");

        $contacts = [
            new ContactDTO(
                fname:   'Alice',
                lname:   'Johnson',
                email:   'alice@example.com',
                msg:     'Hello! I love your blog. Keep up the great work.',
                status:  0,
                created: date('Y-m-d H:i:s')
            ),
            new ContactDTO(
                fname:   'Bob',
                lname:   'Smith',
                email:   'bob@example.com',
                msg:     'I have a question about your PHP OOP article. Can you cover traits in a follow-up post?',
                status:  0,
                created: date('Y-m-d H:i:s')
            ),
            new ContactDTO(
                fname:   'Carol',
                lname:   'Williams',
                email:   'carol@example.com',
                msg:     'Would love to contribute a guest post on Laravel best practices.',
                status:  1,
                created: date('Y-m-d H:i:s')
            ),
        ];

        foreach ($contacts as $dto) {
            $stmt->execute($dto->toArray());
            echo "  Seeded contact: {$dto->email}\n";
        }
    }
}
