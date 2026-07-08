<?php

use App\Contracts\PostRepositoryInterface;

class Post implements PostRepositoryInterface
{
    public function __construct(private Database $db) {}

    public function getPaginated(int $offset, int $limit): array
    {
        return $this->db->fetchAll(
            "SELECT p.*, c.name AS cat_name, c.slug AS cat_slug
             FROM posts p
             LEFT JOIN categories c ON p.category_id = c.id
             ORDER BY p.date DESC LIMIT ?, ?",
            'ii', [$offset, $limit]
        );
    }

    public function getTotalCount(): int
    {
        return (int) $this->db->fetchColumn("SELECT COUNT(*) FROM posts");
    }

    public function getBySlug(string $slug): array|false
    {
        return $this->db->fetchOne(
            "SELECT p.*, c.name AS cat_name, c.slug AS cat_slug
             FROM posts p
             LEFT JOIN categories c ON p.category_id = c.id
             WHERE p.slug = ? LIMIT 1",
            's', [$slug]
        );
    }

    public function getById(int $id): array|false
    {
        return $this->db->fetchOne(
            "SELECT p.*, c.name AS cat_name, c.slug AS cat_slug
             FROM posts p
             LEFT JOIN categories c ON p.category_id = c.id
             WHERE p.id = ? LIMIT 1",
            'i', [$id]
        );
    }

    public function getRelated(int $categoryId, int $excludeId, int $limit = 6): array
    {
        return $this->db->fetchAll(
            "SELECT id, slug, title, image FROM posts
             WHERE category_id = ? AND id <> ?
             ORDER BY id DESC
             LIMIT ?",
            'iii', [$categoryId, $excludeId, $limit]
        );
    }

    public function search(string $term): array
    {
        $likeTerm = '%' . $term . '%';
        return $this->db->fetchAll(
            "SELECT p.*, c.name AS cat_name, c.slug AS cat_slug
             FROM posts p
             LEFT JOIN categories c ON p.category_id = c.id
             WHERE p.title LIKE ? OR p.body LIKE ?
             ORDER BY p.date DESC",
            'ss', [$likeTerm, $likeTerm]
        );
    }

    public function getByCategory(int $categoryId): array
    {
        return $this->db->fetchAll(
            "SELECT p.*, c.name AS cat_name, c.slug AS cat_slug
             FROM posts p
             LEFT JOIN categories c ON p.category_id = c.id
             WHERE p.category_id = ?
             ORDER BY p.date DESC",
            'i', [$categoryId]
        );
    }

    public function getLatest(int $limit = 5): array
    {
        return $this->db->fetchAll(
            "SELECT id, slug, title, image, body FROM posts ORDER BY id DESC LIMIT ?",
            'i', [$limit]
        );
    }

    public function getAllWithCategory(): array
    {
        return $this->db->fetchAll(
            "SELECT p.*, c.name AS cat_name, c.slug AS cat_slug
             FROM posts p
             INNER JOIN categories c ON p.category_id = c.id
             ORDER BY p.date DESC"
        );
    }

    public function create(array $data): bool
    {
        return (bool) $this->db->insert(
            "INSERT INTO posts (title, slug, body, category_id, author, image, user_id, date)
             VALUES (?, ?, ?, ?, ?, ?, ?, NOW())",
            'sssissi',
            [
                $data['title'],
                $data['slug'],
                $data['body'],
                $data['category_id'],
                $data['author'],
                $data['image'],
                $data['user_id']
            ]
        );
    }

    public function update(int $id, array $data): bool
    {
        return (bool) $this->db->update(
            "UPDATE posts SET
                title = ?, slug = ?, body = ?,
                category_id = ?, author = ?, user_id = ?
             WHERE id = ?",
            'sssissi',
            [
                $data['title'],
                $data['slug'],
                $data['body'],
                $data['category_id'],
                $data['author'],
                $data['user_id'],
                $id
            ]
        );
    }

    public function updateWithImage(int $id, array $data, string $imagePath): bool
    {
        return (bool) $this->db->update(
            "UPDATE posts SET
                title = ?, slug = ?, body = ?,
                category_id = ?, author = ?, image = ?, user_id = ?
             WHERE id = ?",
            'sssissii',
            [
                $data['title'],
                $data['slug'],
                $data['body'],
                $data['category_id'],
                $data['author'],
                $imagePath,
                $data['user_id'],
                $id
            ]
        );
    }

    public function delete(int $id): bool
    {
        return (bool) $this->db->delete(
            "DELETE FROM posts WHERE id = ?",
            'i', [$id]
        );
    }
}
