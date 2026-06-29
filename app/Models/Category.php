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

    public function getByParam(string $param): array|false
    {
        $param = $this->db->escape(trim($param));
        if (is_numeric($param)) {
            $result = $this->db->select("SELECT * FROM categories WHERE id = $param LIMIT 1");
        } else {
            $result = $this->db->select("SELECT * FROM categories WHERE slug = '$param' LIMIT 1");
        }
        return $result ? $result->fetch_assoc() : false;
    }

    public function create(string $name): bool
    {
        $name = $this->db->escape(trim($name));
        $slug = Format::slugify($name);
        return $this->db->insert(
            "INSERT INTO categories (name, slug) VALUES ('$name', '$slug')"
        );
    }

    public function update(int $id, string $name): bool
    {
        $name = $this->db->escape(trim($name));
        $slug = Format::slugify($name);
        return $this->db->update(
            "UPDATE categories SET name = '$name', slug = '$slug' WHERE id = $id"
        );
    }

    public function delete(int $id): bool
    {
        return $this->db->delete("DELETE FROM categories WHERE id = $id");
    }
}


