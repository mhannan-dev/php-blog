<?php

/**
 * Post — all database operations for blog posts.
 */
class Post
{
    public function __construct(private Database $db) {}

    public function getPaginated(int $offset, int $limit): mysqli_result|false
    {
        return $this->db->select(
            "SELECT * FROM posts ORDER BY date DESC LIMIT $offset, $limit"
        );
    }

    public function getTotalCount(): int
    {
        $result = $this->db->link->query("SELECT COUNT(*) AS total FROM posts");
        return $result ? (int) $result->fetch_assoc()['total'] : 0;
    }

    public function getBySlug(string $slug): array|false
    {
        $slug = $this->db->escape($slug);
        $result = $this->db->select(
            "SELECT * FROM posts WHERE slug = '$slug' LIMIT 1"
        );
        return $result ? $result->fetch_assoc() : false;
    }

    public function getById(int $id): array|false
    {
        $result = $this->db->select(
            "SELECT * FROM posts WHERE id = $id LIMIT 1"
        );
        return $result ? $result->fetch_assoc() : false;
    }

    public function getRelated(int $categoryId, int $excludeId, int $limit = 6): mysqli_result|false
    {
        return $this->db->select(
            "SELECT id, slug, title, image FROM posts
             WHERE category_id = $categoryId AND id <> $excludeId
             ORDER BY RAND()
             LIMIT $limit"
        );
    }

    public function search(string $term): mysqli_result|false
    {
        $term = $this->db->escape($term);
        return $this->db->select(
            "SELECT * FROM posts
             WHERE title LIKE '%$term%' OR body LIKE '%$term%'"
        );
    }

    public function getByCategory(int $categoryId): mysqli_result|false
    {
        return $this->db->select(
            "SELECT * FROM posts WHERE category_id = $categoryId ORDER BY date DESC"
        );
    }

    public function getLatest(int $limit = 5): mysqli_result|false
    {
        return $this->db->select(
            "SELECT id, slug, title, image, body FROM posts ORDER BY id DESC LIMIT $limit"
        );
    }

    public function getAllWithCategory(): mysqli_result|false
    {
        return $this->db->select(
            "SELECT p.*, c.name AS cat_name, c.slug AS cat_slug
             FROM posts p
             INNER JOIN categories c ON p.category_id = c.id
             ORDER BY p.date DESC"
        );
    }

    public function create(
        string $title, string $slug, string $body, int $categoryId,
        string $author, string $image, int $userId
    ): bool {
        $title  = $this->db->escape($title);
        $slug   = $this->db->escape($slug);
        $body   = $this->db->escape($body);
        $author = $this->db->escape($author);
        $image  = $this->db->escape($image);

        return $this->db->insert(
            "INSERT INTO posts (title, slug, body, category_id, author, image, user_id)
             VALUES ('$title', '$slug', '$body', $categoryId, '$author', '$image', $userId)"
        );
    }

    public function update(int $id, array $data): bool
    {
        $title  = $this->db->escape($data['title']);
        $slug   = $this->db->escape($data['slug']);
        $body   = $this->db->escape($data['body']);
        $cat    = (int) $data['category_id'];
        $author = $this->db->escape($data['author']);
        $userId = (int) $data['user_id'];

        return $this->db->update(
            "UPDATE posts SET
                title       = '$title',
                slug        = '$slug',
                body        = '$body',
                category_id = $cat,
                author      = '$author',
                user_id     = $userId
             WHERE id = $id"
        );
    }

    public function updateWithImage(int $id, array $data, string $imagePath): bool
    {
        $title  = $this->db->escape($data['title']);
        $slug   = $this->db->escape($data['slug']);
        $body   = $this->db->escape($data['body']);
        $cat    = (int) $data['category_id'];
        $author = $this->db->escape($data['author']);
        $userId = (int) $data['user_id'];
        $image  = $this->db->escape($imagePath);

        return $this->db->update(
            "UPDATE posts SET
                title       = '$title',
                slug        = '$slug',
                body        = '$body',
                category_id = $cat,
                author      = '$author',
                image       = '$image',
                user_id     = $userId
             WHERE id = $id"
        );
    }

    public function delete(int $id): bool
    {
        return $this->db->delete("DELETE FROM posts WHERE id = $id");
    }
}
