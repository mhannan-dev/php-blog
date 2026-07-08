<?php

use App\Contracts\CategoryRepositoryInterface;

class Category implements CategoryRepositoryInterface
{
    public function __construct(private Database $db) {}

    public function getAll(): array
    {
        return $this->db->fetchAll("SELECT * FROM categories ORDER BY name ASC");
    }

    public function getById(int $id): array|false
    {
        return $this->db->fetchOne(
            "SELECT * FROM categories WHERE id = ? LIMIT 1",
            'i', [$id]
        );
    }

    public function getByParam(string $param): array|false
    {
        if (is_numeric($param)) {
            return $this->db->fetchOne(
                "SELECT * FROM categories WHERE id = ? LIMIT 1",
                'i', [(int) $param]
            );
        }
        return $this->db->fetchOne(
            "SELECT * FROM categories WHERE slug = ? LIMIT 1",
            's', [$param]
        );
    }

    public function create(string $name): bool
    {
        $slug = Format::slugify($name);
        return (bool) $this->db->insert(
            "INSERT INTO categories (name, slug) VALUES (?, ?)",
            'ss', [trim($name), $slug]
        );
    }

    public function update(int $id, string $name): bool
    {
        $slug = Format::slugify($name);
        return (bool) $this->db->update(
            "UPDATE categories SET name = ?, slug = ? WHERE id = ?",
            'ssi', [trim($name), $slug, $id]
        );
    }

    public function delete(int $id): bool
    {
        return (bool) $this->db->delete(
            "DELETE FROM categories WHERE id = ?",
            'i', [$id]
        );
    }
}
