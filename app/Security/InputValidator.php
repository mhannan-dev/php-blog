<?php

namespace App\Security;

class InputValidator
{
    private array $errors = [];

    public function required(string $field, mixed $value, string $label = ''): static
    {
        $label = $label ?: $field;
        if ($value === null || $value === '' || (is_string($value) && trim($value) === '')) {
            $this->errors[$field] = "$label is required.";
        }
        return $this;
    }

    public function email(string $field, mixed $value): static
    {
        if (!empty($value) && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $this->errors[$field] = 'Please enter a valid email address.';
        }
        return $this;
    }

    public function minLength(string $field, mixed $value, int $min, string $label = ''): static
    {
        $label = $label ?: $field;
        if (!empty($value) && mb_strlen((string) $value) < $min) {
            $this->errors[$field] = "$label must be at least $min characters.";
        }
        return $this;
    }

    public function maxLength(string $field, mixed $value, int $max, string $label = ''): static
    {
        $label = $label ?: $field;
        if (!empty($value) && mb_strlen((string) $value) > $max) {
            $this->errors[$field] = "$label must not exceed $max characters.";
        }
        return $this;
    }

    public function numeric(string $field, mixed $value, string $label = ''): static
    {
        $label = $label ?: $field;
        if (!empty($value) && !is_numeric($value)) {
            $this->errors[$field] = "$label must be a number.";
        }
        return $this;
    }

    public function inArray(string $field, mixed $value, array $allowed, string $label = ''): static
    {
        $label = $label ?: $field;
        if (!empty($value) && !in_array((string) $value, $allowed, true)) {
            $this->errors[$field] = "Invalid $label selected.";
        }
        return $this;
    }

    public function url(string $field, mixed $value): static
    {
        if (!empty($value) && !filter_var($value, FILTER_VALIDATE_URL)) {
            $this->errors[$field] = 'Please enter a valid URL.';
        }
        return $this;
    }

    public function passes(): bool
    {
        return empty($this->errors);
    }

    public function errors(): array
    {
        return $this->errors;
    }

    public function firstError(): string
    {
        return !empty($this->errors) ? reset($this->errors) : '';
    }

    public function clear(): void
    {
        $this->errors = [];
    }

    public static function sanitize(string $value): string
    {
        return htmlspecialchars(trim($value), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }

    public static function sanitizeArray(array $data, array $keys = []): array
    {
        if (empty($keys)) {
            $keys = array_keys($data);
        }
        $sanitized = [];
        foreach ($keys as $key) {
            if (isset($data[$key]) && is_string($data[$key])) {
                $sanitized[$key] = self::sanitize($data[$key]);
            } elseif (isset($data[$key])) {
                $sanitized[$key] = $data[$key];
            }
        }
        return $sanitized;
    }
}
