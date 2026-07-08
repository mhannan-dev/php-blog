<?php

namespace App\Contracts;

interface PageRepositoryInterface
{
    public function getAll(): array;
    public function getBySlug(string $slug): array|false;
    public function getById(int $id): array|false;
    public function create(array $data): bool;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
}
