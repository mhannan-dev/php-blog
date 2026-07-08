<?php

namespace App\Contracts;

interface ContactRepositoryInterface
{
    public function getUnread(): array;
    public function getSeen(): array;
    public function getById(int $id): array|false;
    public function getUnreadCount(): int;
    public function create(string $fname, string $lname, string $email, string $msg): bool;
    public function markAsSeen(int $id): bool;
    public function delete(int $id): bool;
}
