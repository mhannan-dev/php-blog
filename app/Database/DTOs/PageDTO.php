<?php

namespace App\Database\DTOs;

/**
 * Data Transfer Object for a single page row.
 *
 * Carries the data needed to insert one record into the `pages` table.
 */
class PageDTO
{
    public function __construct(
        public readonly string $name,
        public readonly string $body
    ) {}

    /**
     * Return the DTO as an associative array for PDO binding.
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'body' => $this->body,
        ];
    }
}
