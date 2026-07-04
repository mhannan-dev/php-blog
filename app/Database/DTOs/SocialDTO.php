<?php

namespace App\Database\DTOs;

/**
 * Data Transfer Object for the social links row.
 *
 * Carries the data needed to insert one record into the `socials` table.
 */
class SocialDTO
{
    public function __construct(
        public readonly string $fb,
        public readonly string $tw,
        public readonly string $ln
    ) {}

    /**
     * Return the DTO as an associative array for PDO binding.
     */
    public function toArray(): array
    {
        return [
            'fb' => $this->fb,
            'tw' => $this->tw,
            'ln' => $this->ln,
        ];
    }
}
