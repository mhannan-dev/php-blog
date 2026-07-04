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
        public readonly string $slug,
        public readonly string $body,
        public readonly string $meta_title = '',
        public readonly string $meta_description = '',
        public readonly string $meta_keywords = ''
    ) {}

    /**
     * Return the DTO as an associative array for PDO binding.
     */
    public function toArray(): array
    {
        return [
            'name'             => $this->name,
            'slug'             => $this->slug,
            'body'             => $this->body,
            'meta_title'       => $this->meta_title,
            'meta_description' => $this->meta_description,
            'meta_keywords'    => $this->meta_keywords,
        ];
    }
}
