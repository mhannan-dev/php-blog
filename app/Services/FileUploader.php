<?php

namespace App\Services;

class FileUploader
{
    private array $allowedExtensions;
    private int $maxSize;
    private string $uploadDir;

    public function __construct(
        array $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'],
        int $maxSize = 1_048_576,
        string $uploadDir = ''
    ) {
        $this->allowedExtensions = $allowedExtensions;
        $this->maxSize = $maxSize;
        $this->uploadDir = $uploadDir ?: __DIR__ . '/../../admin/';
    }

    public function upload(array $file, string $subDir = ''): array
    {
        $fileName = $file['name'] ?? '';
        $fileSize = $file['size'] ?? 0;
        $fileTmp  = $file['tmp_name'] ?? '';
        $fileExt  = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        if (empty($fileName)) {
            return ['success' => false, 'error' => 'No file uploaded.'];
        }

        if (!in_array($fileExt, $this->allowedExtensions, true)) {
            return ['success' => false, 'error' => 'Allowed image types: ' . implode(', ', $this->allowedExtensions) . '.'];
        }

        if ($fileSize > $this->maxSize) {
            return ['success' => false, 'error' => 'File size must be less than ' . ($this->maxSize / 1048576) . ' MB.'];
        }

        $uniqueName = bin2hex(random_bytes(8)) . '.' . $fileExt;
        $subDir = trim($subDir, '/');
        $relativePath = $subDir ? 'upload/' . $subDir . '/' . $uniqueName : 'upload/' . $uniqueName;
        $fullPath = $this->uploadDir . $relativePath;

        $fullDir = dirname($fullPath);
        if (!is_dir($fullDir)) {
            mkdir($fullDir, 0775, true);
        }

        if (!move_uploaded_file($fileTmp, $fullPath)) {
            return ['success' => false, 'error' => 'Failed to upload file. Check folder permissions.'];
        }

        return ['success' => true, 'path' => $relativePath, 'name' => $uniqueName];
    }

    public function validate(array $file): ?string
    {
        $fileName = $file['name'] ?? '';
        $fileSize = $file['size'] ?? 0;
        $fileExt  = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        if (empty($fileName)) {
            return null;
        }

        if (!in_array($fileExt, $this->allowedExtensions, true)) {
            return 'Allowed image types: ' . implode(', ', $this->allowedExtensions) . '.';
        }

        if ($fileSize > $this->maxSize) {
            return 'File size must be less than ' . ($this->maxSize / 1048576) . ' MB.';
        }

        return null;
    }
}
