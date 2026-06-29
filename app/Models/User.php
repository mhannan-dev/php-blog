<?php

/**
 * User — all database operations for admin users.
 */
class User
{
    public function __construct(private Database $db) {}

    public function getByUsername(string $username): array|false
    {
        $username = $this->db->escape($username);
        $result   = $this->db->select(
            "SELECT * FROM users WHERE username = '$username' LIMIT 1"
        );
        return $result ? $result->fetch_assoc() : false;
    }

    public function getById(int $id): array|false
    {
        $result = $this->db->select(
            "SELECT * FROM users WHERE id = $id LIMIT 1"
        );
        return $result ? $result->fetch_assoc() : false;
    }

    public function getAll(): mysqli_result|false
    {
        return $this->db->select("SELECT * FROM users ORDER BY id DESC");
    }

    public function usernameExists(string $username, int $excludeId = 0): bool
    {
        $username = $this->db->escape($username);
        $exclude  = $excludeId > 0 ? " AND id <> $excludeId" : '';
        $result   = $this->db->select(
            "SELECT id FROM users WHERE username = '$username'$exclude LIMIT 1"
        );
        return $result !== false;
    }

    public function create(string $username, string $passwordHash, int $role): bool
    {
        $username = $this->db->escape($username);
        $hash     = $this->db->escape($passwordHash);
        return $this->db->insert(
            "INSERT INTO users (username, password, role)
             VALUES ('$username', '$hash', $role)"
        );
    }

    public function update(int $id, array $data): bool
    {
        $name     = $this->db->escape(trim($data['name']     ?? ''));
        $username = $this->db->escape(trim($data['username'] ?? ''));
        $email    = $this->db->escape(trim($data['email']    ?? ''));
        $details  = $this->db->escape(trim($data['details']  ?? ''));

        return $this->db->update(
            "UPDATE users SET
                name     = '$name',
                username = '$username',
                email    = '$email',
                details  = '$details'
             WHERE id = $id"
        );
    }

    public function updatePassword(int $id, string $hash): bool
    {
        $hash = $this->db->escape($hash);
        return $this->db->update(
            "UPDATE users SET password = '$hash' WHERE id = $id"
        );
    }

    public function delete(int $id): bool
    {
        return $this->db->delete("DELETE FROM users WHERE id = $id");
    }

    /**
     * Return a human-readable role label.
     */
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
