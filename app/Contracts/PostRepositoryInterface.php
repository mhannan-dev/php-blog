<?php

namespace App\Contracts;

interface PostRepositoryInterface
{
    public function getPaginated(int $offset, int $limit): array;
    public function getTotalCount(): int;
    public function getBySlug(string $slug): array|false;
    public function getById(int $id): array|false;
    public function getRelated(int $categoryId, int $excludeId, int $limit = 6): array;
    public function search(string $term): array;
    public function getByCategory(int $categoryId): array;
    public function getLatest(int $limit = 5): array;
    public function getAllWithCategory(): array;
    public function create(array $data): bool;
    public function update(int $id, array $data): bool;
    public function updateWithImage(int $id, array $data, string $imagePath): bool;
    public function delete(int $id): bool;
}
