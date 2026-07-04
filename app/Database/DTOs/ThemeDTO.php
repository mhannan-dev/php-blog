<?php

namespace App\Database\DTOs;

/**
 * Data Transfer Object for a single theme row.
 *
 * Carries the data needed to insert one record into the `themes` table.
 */
class ThemeDTO
{
    public function __construct(
        public readonly string $theme
    ) {}

    /**
     * Return the DTO as an associative array for PDO binding.
     */
    public function toArray(): array
    {
        return [
            'theme' => $this->theme,
        ];
    }
}
