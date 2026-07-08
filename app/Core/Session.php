<?php

/**
 * Session helper — thin static wrapper around PHP sessions
 * with enhanced security features.
 */
class Session
{
    /**
     * Start the session with secure settings if not already active.
     */
    public static function init(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            ini_set('session.use_strict_mode', '1');
            ini_set('session.use_only_cookies', '1');
            ini_set('session.cookie_httponly', '1');
            ini_set('session.cookie_samesite', 'Lax');

            if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
                ini_set('session.cookie_secure', '1');
            }

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
     * Regenerate session ID to prevent session fixation.
     */
    public static function regenerate(): void
    {
        self::init();
        session_regenerate_id(true);
    }

    /**
     * Verify the user is logged in; redirect to login if not.
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

        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params['path'], $params['domain'],
            $params['secure'] ?? false, $params['httponly'] ?? true
        );

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

    /**
     * Refresh the CSRF token (call after successful form processing).
     */
    public static function refreshCsrfToken(): void
    {
        self::init();
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
}
