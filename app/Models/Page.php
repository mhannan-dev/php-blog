<?php

/**
 * Page — all database operations for static pages.
 */
class Page
{
    public function __construct(private Database $db) {}

    public function getAll(): mysqli_result|false
    {
        return $this->db->select("SELECT * FROM pages ORDER BY id ASC");
    }

    public function getById(int $id): array|false
    {
        $result = $this->db->select(
            "SELECT * FROM pages WHERE id = $id LIMIT 1"
        );
        return $result ? $result->fetch_assoc() : false;
    }

    public function getByTitle(int $id): array|false
    {
        $result = $this->db->select(
            "SELECT name FROM pages WHERE id = $id LIMIT 1"
        );
        return $result ? $result->fetch_assoc() : false;
    }

    public function create(string $name, string $body): bool
    {
        $name = $this->db->escape(trim($name));
        $body = $this->db->escape($body);
        return $this->db->insert(
            "INSERT INTO pages (name, body) VALUES ('$name', '$body')"
        );
    }

    public function update(int $id, string $name, string $body): bool
    {
        $name = $this->db->escape(trim($name));
        $body = $this->db->escape($body);
        return $this->db->update(
            "UPDATE pages SET name = '$name', body = '$body' WHERE id = $id"
        );
    }

    public function delete(int $id): bool
    {
        return $this->db->delete("DELETE FROM pages WHERE id = $id");
    }
}
