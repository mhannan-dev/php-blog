<?php

namespace App\Database\DTOs;

/**
 * Data Transfer Object for a single contact row.
 *
 * Carries the data needed to insert one record into the `contacts` table.
 */
class ContactDTO
{
    public function __construct(
        public readonly string $fname,
        public readonly string $lname,
        public readonly string $email,
        public readonly string $msg,
        public readonly int    $status,
        public readonly string $created
    ) {}

    /**
     * Return the DTO as an associative array for PDO binding.
     */
    public function toArray(): array
    {
        return [
            'fname'   => $this->fname,
            'lname'   => $this->lname,
            'email'   => $this->email,
            'msg'     => $this->msg,
            'status'  => $this->status,
            'created' => $this->created,
        ];
    }
}
