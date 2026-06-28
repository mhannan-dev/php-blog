<?php

/**
 * Category — all database operations for post categories.
 */
class Category
{
    public function __construct(private Database $db) {}

    public function getAll(): mysqli_result|false
    {
        return $this->db->select("SELECT * FROM categories ORDER BY name ASC");
    }

    public function getById(int $id): array|false
    {
        $result = $this->db->select(
            "SELECT * FROM categories WHERE id = $id LIMIT 1"
        );
        return $result ? $result->fetch_assoc() : false;
    }

    public function create(string $name): bool
    {
        $name = $this->db->escape(trim($name));
        return $this->db->insert(
            "INSERT INTO categories (name) VALUES ('$name')"
        );
    }

    public function update(int $id, string $name): bool
    {
        $name = $this->db->escape(trim($name));
        return $this->db->update(
            "UPDATE categories SET name = '$name' WHERE id = $id"
        );
    }

    public function delete(int $id): bool
    {
        return $this->db->delete("DELETE FROM categories WHERE id = $id");
    }
}


