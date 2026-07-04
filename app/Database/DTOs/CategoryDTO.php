<?php

namespace App\Database\DTOs;

/**
 * Data Transfer Object for a single category row.
 *
 * Carries the data needed to insert one record into the `categories` table.
 */
class CategoryDTO
{
    public function __construct(
        public readonly string $name
    ) {}

    /**
     * Return the DTO as an associative array for PDO binding.
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
        ];
    }
}
