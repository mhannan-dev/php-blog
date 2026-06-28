<?php

/**
 * Site — all database operations for site configuration
 * (site info, social links, footer, sliders).
 */
class Site
{
    public function __construct(private Database $db) {}

    public function getInfo(): array|false
    {
        $result = $this->db->select("SELECT * FROM site_infos LIMIT 1");
        return $result ? $result->fetch_assoc() : false;
    }

    public function getSocialLinks(): array|false
    {
        $result = $this->db->select(
            "SELECT * FROM socials ORDER BY id LIMIT 1"
        );
        return $result ? $result->fetch_assoc() : false;
    }

    public function getFooterNote(): array|false
    {
        $result = $this->db->select("SELECT note FROM footers LIMIT 1");
        return $result ? $result->fetch_assoc() : false;
    }

    public function getSliders(int $limit = 4): mysqli_result|false
    {
        return $this->db->select(
            "SELECT * FROM sliders ORDER BY id DESC LIMIT $limit"
        );
    }

    public function getAllSiteInfo(): mysqli_result|false
    {
        return $this->db->select("SELECT * FROM site_infos ORDER BY id");
    }

    public function getAllSocial(): mysqli_result|false
    {
        return $this->db->select("SELECT * FROM socials ORDER BY id");
    }

    public function getSocialById(int $id): array|false
    {
        $result = $this->db->select(
            "SELECT * FROM socials WHERE id = $id LIMIT 1"
        );
        return $result ? $result->fetch_assoc() : false;
    }

    public function getSiteInfoById(int $id): array|false
    {
        $result = $this->db->select(
            "SELECT * FROM site_infos WHERE id = $id LIMIT 1"
        );
        return $result ? $result->fetch_assoc() : false;
    }

    public function getSliderById(int $id): array|false
    {
        $result = $this->db->select(
            "SELECT * FROM sliders WHERE id = $id LIMIT 1"
        );
        return $result ? $result->fetch_assoc() : false;
    }

    public function createSiteInfo(string $logo, string $title, string $slogan): bool
    {
        $logo   = $this->db->escape($logo);
        $title  = $this->db->escape($title);
        $slogan = $this->db->escape($slogan);
        return $this->db->insert(
            "INSERT INTO site_infos (logo, title, slogan)
             VALUES ('$logo', '$title', '$slogan')"
        );
    }

    public function updateSiteInfo(int $id, string $title, string $slogan, ?string $logo = null): bool
    {
        $title  = $this->db->escape($title);
        $slogan = $this->db->escape($slogan);
        if ($logo !== null) {
            $logo = $this->db->escape($logo);
            return $this->db->update(
                "UPDATE site_infos SET logo = '$logo', title = '$title', slogan = '$slogan' WHERE id = $id"
            );
        }
        return $this->db->update(
            "UPDATE site_infos SET title = '$title', slogan = '$slogan' WHERE id = $id"
        );
    }

    public function deleteSiteInfo(int $id): bool
    {
        return $this->db->delete("DELETE FROM site_infos WHERE id = $id");
    }

    public function createSocial(string $fb, string $tw, string $ln): bool
    {
        $fb = $this->db->escape($fb);
        $tw = $this->db->escape($tw);
        $ln = $this->db->escape($ln);
        return $this->db->insert(
            "INSERT INTO socials (fb, tw, ln) VALUES ('$fb', '$tw', '$ln')"
        );
    }

    public function updateSocial(int $id, string $fb, string $tw, string $ln): bool
    {
        $fb = $this->db->escape($fb);
        $tw = $this->db->escape($tw);
        $ln = $this->db->escape($ln);
        return $this->db->update(
            "UPDATE socials SET fb = '$fb', tw = '$tw', ln = '$ln' WHERE id = $id"
        );
    }

    public function deleteSocial(int $id): bool
    {
        return $this->db->delete("DELETE FROM socials WHERE id = $id");
    }

    public function updateFooter(string $note): bool
    {
        $note = $this->db->escape($note);
        return $this->db->update(
            "UPDATE footers SET note = '$note' WHERE id = 1"
        );
    }

    public function createSlider(string $image, string $title): bool
    {
        $image = $this->db->escape($image);
        $title = $this->db->escape($title);
        return $this->db->insert(
            "INSERT INTO sliders (image, title) VALUES ('$image', '$title')"
        );
    }

    public function updateSlider(int $id, string $title, ?string $image = null): bool
    {
        $title = $this->db->escape($title);
        if ($image !== null) {
            $image = $this->db->escape($image);
            return $this->db->update(
                "UPDATE sliders SET title = '$title', image = '$image' WHERE id = $id"
            );
        }
        return $this->db->update(
            "UPDATE sliders SET title = '$title' WHERE id = $id"
        );
    }

    public function deleteSlider(int $id): bool
    {
        return $this->db->delete("DELETE FROM sliders WHERE id = $id");
    }
}
