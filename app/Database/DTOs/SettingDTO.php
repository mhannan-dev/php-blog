<?php

namespace App\Database\DTOs;

/**
 * Data Transfer Object for the site settings row.
 *
 * Carries the data needed to insert one record into the `settings` table.
 */
class SettingDTO
{
    public function __construct(
        public readonly string $logo,
        public readonly string $title,
        public readonly string $slogan
    ) {}

    /**
     * Return the DTO as an associative array for PDO binding.
     */
    public function toArray(): array
    {
        return [
            'logo'   => $this->logo,
            'title'  => $this->title,
            'slogan' => $this->slogan,
        ];
    }
}
