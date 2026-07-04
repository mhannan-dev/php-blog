<?php

namespace App\Database\DTOs;

/**
 * Data Transfer Object for a single slider row.
 *
 * Carries the data needed to insert one record into the `sliders` table.
 */
class SliderDTO
{
    public function __construct(
        public readonly string $title,
        public readonly string $image
    ) {}

    /**
     * Return the DTO as an associative array for PDO binding.
     */
    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'image' => $this->image,
        ];
    }
}
