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

    public function getBySlug(string $slug): array|false
    {
        $slug = $this->db->escape($slug);
        $result = $this->db->select(
            "SELECT * FROM pages WHERE slug = '$slug' LIMIT 1"
        );
        return $result ? $result->fetch_assoc() : false;
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

    public function create(
        string $name, string $slug, string $body,
        string $metaTitle = '', string $metaDescription = '', string $metaKeywords = ''
    ): bool {
        $name = $this->db->escape(trim($name));
        $slug = $this->db->escape(trim($slug));
        $body = $this->db->escape($body);
        $metaTitle = $this->db->escape(trim($metaTitle));
        $metaDescription = $this->db->escape(trim($metaDescription));
        $metaKeywords = $this->db->escape(trim($metaKeywords));
        
        return $this->db->insert(
            "INSERT INTO pages (name, slug, body, meta_title, meta_description, meta_keywords) 
             VALUES ('$name', '$slug', '$body', '$metaTitle', '$metaDescription', '$metaKeywords')"
        );
    }

    public function update(
        int $id, string $name, string $slug, string $body,
        string $metaTitle = '', string $metaDescription = '', string $metaKeywords = ''
    ): bool {
        $name = $this->db->escape(trim($name));
        $slug = $this->db->escape(trim($slug));
        $body = $this->db->escape($body);
        $metaTitle = $this->db->escape(trim($metaTitle));
        $metaDescription = $this->db->escape(trim($metaDescription));
        $metaKeywords = $this->db->escape(trim($metaKeywords));
        
        return $this->db->update(
            "UPDATE pages SET 
                name = '$name', 
                slug = '$slug', 
                body = '$body',
                meta_title = '$metaTitle',
                meta_description = '$metaDescription',
                meta_keywords = '$metaKeywords'
             WHERE id = $id"
        );
    }

    public function delete(int $id): bool
    {
        return $this->db->delete("DELETE FROM pages WHERE id = $id");
    }
}
