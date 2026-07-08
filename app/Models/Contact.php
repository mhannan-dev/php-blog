<?php

use App\Contracts\ContactRepositoryInterface;

class Contact implements ContactRepositoryInterface
{
    public function __construct(private Database $db) {}

    public function getUnread(): array
    {
        return $this->db->fetchAll(
            "SELECT * FROM contacts WHERE status = '0' ORDER BY id DESC"
        );
    }

    public function getSeen(): array
    {
        return $this->db->fetchAll(
            "SELECT * FROM contacts WHERE status = '1' ORDER BY id DESC"
        );
    }

    public function getById(int $id): array|false
    {
        return $this->db->fetchOne(
            "SELECT * FROM contacts WHERE id = ? LIMIT 1",
            'i', [$id]
        );
    }

    public function getUnreadCount(): int
    {
        return (int) $this->db->fetchColumn(
            "SELECT COUNT(*) FROM contacts WHERE status = '0'"
        );
    }

    public function create(string $fname, string $lname, string $email, string $msg): bool
    {
        return (bool) $this->db->insert(
            "INSERT INTO contacts (fname, lname, email, msg) VALUES (?, ?, ?, ?)",
            'ssss', [$fname, $lname, $email, $msg]
        );
    }

    public function markAsSeen(int $id): bool
    {
        return (bool) $this->db->update(
            "UPDATE contacts SET status = '1' WHERE id = ?",
            'i', [$id]
        );
    }

    public function delete(int $id): bool
    {
        return (bool) $this->db->delete(
            "DELETE FROM contacts WHERE id = ?",
            'i', [$id]
        );
    }
}
