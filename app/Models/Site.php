<?php

use App\Contracts\SiteRepositoryInterface;

class Site implements SiteRepositoryInterface
{
    public function __construct(private Database $db) {}

    // ── Site Info ─────────────────────────────────────────────────────────

    public function getInfo(): array|false
    {
        return $this->db->fetchOne("SELECT * FROM settings LIMIT 1");
    }

    public function getAllSiteInfo(): array
    {
        return $this->db->fetchAll("SELECT * FROM settings ORDER BY id");
    }

    public function getSiteInfoById(int $id): array|false
    {
        return $this->db->fetchOne(
            "SELECT * FROM settings WHERE id = ? LIMIT 1",
            'i', [$id]
        );
    }

    public function createSiteInfo(string $logo, string $title, string $slogan): bool
    {
        return (bool) $this->db->insert(
            "INSERT INTO settings (logo, title, slogan) VALUES (?, ?, ?)",
            'sss', [$logo, $title, $slogan]
        );
    }

    public function updateSiteInfo(int $id, string $title, string $slogan, ?string $logo = null): bool
    {
        if ($logo !== null) {
            return (bool) $this->db->update(
                "UPDATE settings SET logo = ?, title = ?, slogan = ? WHERE id = ?",
                'sssi', [$logo, $title, $slogan, $id]
            );
        }
        return (bool) $this->db->update(
            "UPDATE settings SET title = ?, slogan = ? WHERE id = ?",
            'ssi', [$title, $slogan, $id]
        );
    }

    public function deleteSiteInfo(int $id): bool
    {
        return (bool) $this->db->delete(
            "DELETE FROM settings WHERE id = ?",
            'i', [$id]
        );
    }

    // ── Social Links ──────────────────────────────────────────────────────

    public function getSocialLinks(): array|false
    {
        return $this->db->fetchOne("SELECT * FROM socials ORDER BY id LIMIT 1");
    }

    public function getAllSocial(): array
    {
        return $this->db->fetchAll("SELECT * FROM socials ORDER BY id");
    }

    public function getSocialById(int $id): array|false
    {
        return $this->db->fetchOne(
            "SELECT * FROM socials WHERE id = ? LIMIT 1",
            'i', [$id]
        );
    }

    public function createSocial(string $fb, string $tw, string $ln): bool
    {
        return (bool) $this->db->insert(
            "INSERT INTO socials (fb, tw, ln) VALUES (?, ?, ?)",
            'sss', [$fb, $tw, $ln]
        );
    }

    public function updateSocial(int $id, string $fb, string $tw, string $ln): bool
    {
        return (bool) $this->db->update(
            "UPDATE socials SET fb = ?, tw = ?, ln = ? WHERE id = ?",
            'sssi', [$fb, $tw, $ln, $id]
        );
    }

    public function deleteSocial(int $id): bool
    {
        return (bool) $this->db->delete(
            "DELETE FROM socials WHERE id = ?",
            'i', [$id]
        );
    }

    // ── Footer ────────────────────────────────────────────────────────────

    public function getFooterNote(): array|false
    {
        return $this->db->fetchOne("SELECT note FROM footers LIMIT 1");
    }

    public function updateFooter(string $note): bool
    {
        return (bool) $this->db->update(
            "UPDATE footers SET note = ? WHERE id = 1",
            's', [$note]
        );
    }

    // ── Sliders ───────────────────────────────────────────────────────────

    public function getSliders(int $limit = 4): array
    {
        return $this->db->fetchAll(
            "SELECT * FROM sliders ORDER BY id DESC LIMIT ?",
            'i', [$limit]
        );
    }

    public function getSliderById(int $id): array|false
    {
        return $this->db->fetchOne(
            "SELECT * FROM sliders WHERE id = ? LIMIT 1",
            'i', [$id]
        );
    }

    public function createSlider(string $title, string $description, string $image): bool
    {
        return (bool) $this->db->insert(
            "INSERT INTO sliders (title, description, image) VALUES (?, ?, ?)",
            'sss', [$title, $description, $image]
        );
    }

    public function updateSlider(int $id, string $title, ?string $image = null): bool
    {
        if ($image !== null) {
            return (bool) $this->db->update(
                "UPDATE sliders SET title = ?, image = ? WHERE id = ?",
                'ssi', [$title, $image, $id]
            );
        }
        return (bool) $this->db->update(
            "UPDATE sliders SET title = ? WHERE id = ?",
            'si', [$title, $id]
        );
    }

    public function deleteSlider(int $id): bool
    {
        return (bool) $this->db->delete(
            "DELETE FROM sliders WHERE id = ?",
            'i', [$id]
        );
    }
}
