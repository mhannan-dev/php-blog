<?php

namespace App\Helpers;

class Validator
{
    private array $errors = [];

    /**
     * Check if specified fields are present and not empty in the provided data array.
     */
    public function required(array $data, array $fields): void
    {
        foreach ($fields as $field) {
            if (empty(trim((string)($data[$field] ?? '')))) {
                $this->errors[] = ucfirst(str_replace('_', ' ', $field)) . ' is required.';
            }
        }
    }

    /**
     * Check if the given string is a valid email.
     */
    public function email(string $email): void
    {
        if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->errors[] = 'Please enter a valid email address.';
        }
    }
    
    /**
     * Custom validation rule or manual error addition.
     */
    public function addError(string $error): void
    {
        $this->errors[] = $error;
    }

    public function hasErrors(): bool
    {
        return count($this->errors) > 0;
    }

    public function getFirstError(): string
    {
        return $this->errors[0] ?? '';
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
