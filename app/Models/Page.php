<?php

use App\Contracts\PageRepositoryInterface;

class Page implements PageRepositoryInterface
{
    public function __construct(private Database $db) {}

    public function getAll(): array
    {
        return $this->db->fetchAll("SELECT * FROM pages ORDER BY id ASC");
    }

    public function getBySlug(string $slug): array|false
    {
        return $this->db->fetchOne(
            "SELECT * FROM pages WHERE slug = ? LIMIT 1",
            's', [$slug]
        );
    }

    public function getById(int $id): array|false
    {
        return $this->db->fetchOne(
            "SELECT * FROM pages WHERE id = ? LIMIT 1",
            'i', [$id]
        );
    }

    public function create(array $data): bool
    {
        return (bool) $this->db->insert(
            "INSERT INTO pages (name, slug, body, meta_title, meta_description, meta_keywords)
             VALUES (?, ?, ?, ?, ?, ?)",
            'ssssss',
            [
                trim($data['name']),
                trim($data['slug']),
                $data['body'],
                trim($data['meta_title'] ?? ''),
                trim($data['meta_description'] ?? ''),
                trim($data['meta_keywords'] ?? '')
            ]
        );
    }

    public function update(int $id, array $data): bool
    {
        return (bool) $this->db->update(
            "UPDATE pages SET
                name = ?, slug = ?, body = ?,
                meta_title = ?, meta_description = ?, meta_keywords = ?
             WHERE id = ?",
            'ssssssi',
            [
                trim($data['name']),
                trim($data['slug']),
                $data['body'],
                trim($data['meta_title'] ?? ''),
                trim($data['meta_description'] ?? ''),
                trim($data['meta_keywords'] ?? ''),
                $id
            ]
        );
    }

    public function delete(int $id): bool
    {
        return (bool) $this->db->delete(
            "DELETE FROM pages WHERE id = ?",
            'i', [$id]
        );
    }
}
