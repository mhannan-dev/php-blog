<?php

namespace App\Database\DTOs;

class PostTagDTO
{
    public function __construct(
        public readonly int $post_id,
        public readonly int $tag_id
    ) {}

    public function toArray(): array
    {
        return [
            'post_id' => $this->post_id,
            'tag_id'  => $this->tag_id,
        ];
    }
}
