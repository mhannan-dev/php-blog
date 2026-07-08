<?php

namespace App\Database\DTOs;

class TagDTO
{
    public function __construct(
        public readonly string $name,
        public readonly string $slug
    ) {}

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'slug' => $this->slug,
        ];
    }
}
