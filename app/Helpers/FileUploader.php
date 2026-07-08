<?php

namespace App\Helpers;

class FileUploader
{
    private array $allowedExts = ['jpg', 'jpeg', 'png', 'gif'];
    private int $maxSize = 1048576; // 1 MB
    private string $uploadDir = 'upload/';
    private string $error = '';

    public function upload(array $file): ?string
    {
        if (!isset($file['name']) || empty($file['name'])) {
            return null;
        }

        $fileName = $file['name'];
        $fileSize = $file['size'] ?? 0;
        $fileTmp  = $file['tmp_name'] ?? '';
        $fileExt  = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        if ($fileSize > $this->maxSize) {
            $this->error = 'Image size must be less than 1 MB.';
            return null;
        }

        if (!in_array($fileExt, $this->allowedExts, true)) {
            $this->error = 'Allowed image types: ' . implode(', ', $this->allowedExts) . '.';
            return null;
        }

        $uniqueName = bin2hex(random_bytes(8)) . '.' . $fileExt;
        $uploadedPath = $this->uploadDir . $uniqueName;

        if (!move_uploaded_file($fileTmp, $uploadedPath)) {
            $this->error = 'Failed to upload image. Check folder permissions.';
            return null;
        }

        return $uploadedPath;
    }

    public function getError(): string
    {
        return $this->error;
    }
}
