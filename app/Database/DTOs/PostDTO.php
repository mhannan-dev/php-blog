<?php

namespace App\Database\DTOs;

/**
 * Data Transfer Object for a single post row.
 *
 * Carries the data needed to insert one record into the `posts` table.
 */
class PostDTO
{
    public function __construct(
        public readonly int    $category_id,
        public readonly string $title,
        public readonly string $slug,
        public readonly string $body,
        public readonly string $image,
        public readonly string $author,
        public readonly string $date,
        public readonly int    $user_id,
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
            'category_id'      => $this->category_id,
            'title'            => $this->title,
            'slug'             => $this->slug,
            'body'             => $this->body,
            'image'            => $this->image,
            'author'           => $this->author,
            'date'             => $this->date,
            'user_id'          => $this->user_id,
            'meta_title'       => $this->meta_title,
            'meta_description' => $this->meta_description,
            'meta_keywords'    => $this->meta_keywords,
        ];
    }
}
