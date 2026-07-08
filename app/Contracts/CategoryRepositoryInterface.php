<?php

namespace App\Contracts;

interface CategoryRepositoryInterface
{
    public function getAll(): array;
    public function getById(int $id): array|false;
    public function getByParam(string $param): array|false;
    public function create(string $name): bool;
    public function update(int $id, string $name): bool;
    public function delete(int $id): bool;
}
