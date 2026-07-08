<?php

use App\Contracts\UserRepositoryInterface;

class User implements UserRepositoryInterface
{
    public function __construct(private Database $db) {}

    public function getByUsername(string $username): array|false
    {
        return $this->db->fetchOne(
            "SELECT * FROM users WHERE username = ? LIMIT 1",
            's', [$username]
        );
    }

    public function getByEmail(string $email): array|false
    {
        return $this->db->fetchOne(
            "SELECT * FROM users WHERE email = ? LIMIT 1",
            's', [$email]
        );
    }

    public function getById(int $id): array|false
    {
        return $this->db->fetchOne(
            "SELECT * FROM users WHERE id = ? LIMIT 1",
            'i', [$id]
        );
    }

    public function getAll(): array
    {
        return $this->db->fetchAll("SELECT * FROM users ORDER BY id DESC");
    }

    public function usernameExists(string $username, int $excludeId = 0): bool
    {
        if ($excludeId > 0) {
            $result = $this->db->fetchOne(
                "SELECT id FROM users WHERE username = ? AND id <> ? LIMIT 1",
                'si', [$username, $excludeId]
            );
        } else {
            $result = $this->db->fetchOne(
                "SELECT id FROM users WHERE username = ? LIMIT 1",
                's', [$username]
            );
        }
        return $result !== false;
    }

    public function create(string $username, string $passwordHash, int $role): bool
    {
        return (bool) $this->db->insert(
            "INSERT INTO users (username, password, role) VALUES (?, ?, ?)",
            'ssi', [$username, $passwordHash, $role]
        );
    }

    public function update(int $id, array $data): bool
    {
        return (bool) $this->db->update(
            "UPDATE users SET name = ?, username = ?, email = ?, details = ? WHERE id = ?",
            'ssssi',
            [
                trim($data['name'] ?? ''),
                trim($data['username'] ?? ''),
                trim($data['email'] ?? ''),
                trim($data['details'] ?? ''),
                $id
            ]
        );
    }

    public function updatePassword(int $id, string $hash): bool
    {
        return (bool) $this->db->update(
            "UPDATE users SET password = ? WHERE id = ?",
            'si', [$hash, $id]
        );
    }

    public function delete(int $id): bool
    {
        return (bool) $this->db->delete(
            "DELETE FROM users WHERE id = ?",
            'i', [$id]
        );
    }

    public static function roleLabel(string|int $role): string
    {
        return match ((string) $role) {
            '0'     => 'Admin',
            '1'     => 'Author',
            '2'     => 'Editor',
            default => 'Unknown',
        };
    }
}
