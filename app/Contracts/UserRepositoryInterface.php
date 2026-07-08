<?php

namespace App\Contracts;

interface UserRepositoryInterface
{
    public function getByUsername(string $username): array|false;
    public function getByEmail(string $email): array|false;
    public function getById(int $id): array|false;
    public function getAll(): array;
    public function usernameExists(string $username, int $excludeId = 0): bool;
    public function create(string $username, string $passwordHash, int $role): bool;
    public function update(int $id, array $data): bool;
    public function updatePassword(int $id, string $hash): bool;
    public function delete(int $id): bool;
}
