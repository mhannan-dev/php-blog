<?php

/**
 * Session helper — thin static wrapper around PHP sessions.
 */
class Session
{
    /**
     * Start the session if one is not already active.
     */
    public static function init(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function set(string $key, mixed $val): void
    {
        self::init();
        $_SESSION[$key] = $val;
    }

    public static function get(string $key): mixed
    {
        self::init();
        return $_SESSION[$key] ?? false;
    }

    /**
     * Verify the user is logged in; redirect to login if not.
     * Must be called before any output.
     */
    public static function checkSession(): void
    {
        self::init();

        if (self::get('login') !== true) {
            header('Location: login.php');
            exit();
        }
    }

    /**
     * If the user is already logged in, redirect them away from the login page.
     */
    public static function checkLogin(): void
    {
        self::init();

        if (self::get('login') === true) {
            header('Location: index.php');
            exit();
        }
    }

    /**
     * Destroy the current session and redirect to the login page.
     */
    public static function destroy(): void
    {
        self::init();
        $_SESSION = [];
        session_destroy();

        header('Location: login.php');
        exit();
    }

    public static function getCsrfToken(): string
    {
        self::init();
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    public static function checkCsrfToken(?string $token): bool
    {
        self::init();
        $stored = $_SESSION['csrf_token'] ?? '';
        if (empty($stored) || empty($token)) {
            return false;
        }
        return hash_equals($stored, $token);
    }
}
