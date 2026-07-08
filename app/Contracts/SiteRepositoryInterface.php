<?php

namespace App\Contracts;

interface SiteRepositoryInterface
{
    public function getInfo(): array|false;
    public function getAllSiteInfo(): array;
    public function getSiteInfoById(int $id): array|false;
    public function createSiteInfo(string $logo, string $title, string $slogan): bool;
    public function updateSiteInfo(int $id, string $title, string $slogan, ?string $logo = null): bool;
    public function deleteSiteInfo(int $id): bool;
    public function getSocialLinks(): array|false;
    public function getAllSocial(): array;
    public function getSocialById(int $id): array|false;
    public function createSocial(string $fb, string $tw, string $ln): bool;
    public function updateSocial(int $id, string $fb, string $tw, string $ln): bool;
    public function deleteSocial(int $id): bool;
    public function getFooterNote(): array|false;
    public function updateFooter(string $note): bool;
    public function getSliders(int $limit = 4): array;
    public function getSliderById(int $id): array|false;
    public function createSlider(string $title, string $description, string $image): bool;
    public function updateSlider(int $id, string $title, ?string $image = null): bool;
    public function deleteSlider(int $id): bool;
}
