<?php

namespace App\Database\DTOs;

/**
 * Data Transfer Object for a single footer row.
 *
 * Carries the data needed to insert one record into the `footers` table.
 */
class FooterDTO
{
    public function __construct(
        public readonly string $note
    ) {}

    /**
     * Return the DTO as an associative array for PDO binding.
     */
    public function toArray(): array
    {
        return [
            'note' => $this->note,
        ];
    }
}
