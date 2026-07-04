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
        public readonly int    $cat,
        public readonly string $title,
        public readonly string $body,
        public readonly string $image,
        public readonly string $author,
        public readonly string $tags,
        public readonly string $date,
        public readonly int    $userid
    ) {}

    /**
     * Return the DTO as an associative array for PDO binding.
     */
    public function toArray(): array
    {
        return [
            'cat'    => $this->cat,
            'title'  => $this->title,
            'body'   => $this->body,
            'image'  => $this->image,
            'author' => $this->author,
            'tags'   => $this->tags,
            'date'   => $this->date,
            'userid' => $this->userid,
        ];
    }
}
