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

    public function getById(int $id): array|false
    {
        $result = $this->db->select(
            "SELECT * FROM posts WHERE id = $id LIMIT 1"
        );
        return $result ? $result->fetch_assoc() : false;
    }

    public function getRelated(int $catId, int $excludeId, int $limit = 6): mysqli_result|false
    {
        return $this->db->select(
            "SELECT id, title, image FROM posts
             WHERE cat = $catId AND id <> $excludeId
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

    public function getByCat(int $catId): mysqli_result|false
    {
        return $this->db->select(
            "SELECT * FROM posts WHERE cat = $catId ORDER BY date DESC"
        );
    }

    public function getLatest(int $limit = 5): mysqli_result|false
    {
        return $this->db->select(
            "SELECT id, title, image, body FROM posts ORDER BY id DESC LIMIT $limit"
        );
    }

    public function getAllWithCategory(): mysqli_result|false
    {
        return $this->db->select(
            "SELECT p.*, c.name AS cat_name
             FROM posts p
             INNER JOIN categories c ON p.cat = c.id
             ORDER BY p.date DESC"
        );
    }

    public function create(
        string $title, string $body, int $cat,
        string $author, string $image, string $tags, int $userId
    ): bool {
        $title  = $this->db->escape($title);
        $body   = $this->db->escape($body);
        $author = $this->db->escape($author);
        $image  = $this->db->escape($image);
        $tags   = $this->db->escape($tags);

        return $this->db->insert(
            "INSERT INTO posts (title, body, cat, author, image, tags, userid)
             VALUES ('$title', '$body', $cat, '$author', '$image', '$tags', $userId)"
        );
    }

    public function update(int $id, array $data): bool
    {
        $title  = $this->db->escape($data['title']);
        $body   = $this->db->escape($data['body']);
        $cat    = (int) $data['cat'];
        $author = $this->db->escape($data['author']);
        $tags   = $this->db->escape($data['tags']);
        $userId = (int) $data['userid'];

        return $this->db->update(
            "UPDATE posts SET
                title  = '$title',
                body   = '$body',
                cat    = $cat,
                author = '$author',
                tags   = '$tags',
                userid = $userId
             WHERE id = $id"
        );
    }

    public function updateWithImage(int $id, array $data, string $imagePath): bool
    {
        $title  = $this->db->escape($data['title']);
        $body   = $this->db->escape($data['body']);
        $cat    = (int) $data['cat'];
        $author = $this->db->escape($data['author']);
        $tags   = $this->db->escape($data['tags']);
        $userId = (int) $data['userid'];
        $image  = $this->db->escape($imagePath);

        return $this->db->update(
            "UPDATE posts SET
                title  = '$title',
                body   = '$body',
                cat    = $cat,
                author = '$author',
                image  = '$image',
                tags   = '$tags',
                userid = $userId
             WHERE id = $id"
        );
    }

    public function delete(int $id): bool
    {
        return $this->db->delete("DELETE FROM posts WHERE id = $id");
    }
}
