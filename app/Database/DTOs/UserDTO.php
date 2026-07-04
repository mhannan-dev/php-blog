<?php

namespace App\Database\DTOs;

/**
 * Data Transfer Object for a single user row.
 *
 * Carries the data needed to insert one record into the `users` table.
 */
class UserDTO
{
    public function __construct(
        public readonly string $name,
        public readonly string $username,
        public readonly string $email,
        public readonly string $password,
        public readonly string $details,
        public readonly int    $role,
        public readonly int    $userid
    ) {}

    /**
     * Return the DTO as an associative array for PDO binding.
     */
    public function toArray(): array
    {
        return [
            'name'     => $this->name,
            'username' => $this->username,
            'email'    => $this->email,
            'password' => $this->password,
            'details'  => $this->details,
            'role'     => $this->role,
            'userid'   => $this->userid,
        ];
    }
}
