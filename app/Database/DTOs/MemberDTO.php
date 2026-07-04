<?php

namespace App\Database\DTOs;

/**
 * Data Transfer Object for a single member row.
 *
 * Carries the data needed to insert one record into the `members` table.
 */
class MemberDTO
{
    public function __construct(
        public readonly string $name,
        public readonly string $email,
        public readonly string $username,
        public readonly string $password
    ) {}

    /**
     * Return the DTO as an associative array for PDO binding.
     */
    public function toArray(): array
    {
        return [
            'name'     => $this->name,
            'email'    => $this->email,
            'username' => $this->username,
            'password' => $this->password,
        ];
    }
}
