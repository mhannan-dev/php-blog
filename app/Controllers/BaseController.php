<?php

namespace App\Controllers;

use Twig\Environment;
use Session;
use App\Security\InputValidator;

abstract class BaseController
{
    protected Environment $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    protected function render(string $template, array $data = []): void
    {
        echo $this->twig->render($template, $data);
    }

    protected function requireLogin(): void
    {
        Session::checkSession();
    }

    protected function requireAdmin(): void
    {
        $this->requireLogin();
        if (Session::get('userRole') !== '0') {
            header('Location: index.php');
            exit();
        }
    }

    protected function validateCsrf(array $data, string &$error): bool
    {
        $token = $data['csrf_token'] ?? '';
        if (!Session::checkCsrfToken($token)) {
            $error = 'Security check failed. Please refresh the page.';
            return false;
        }
        return true;
    }

    protected function redirect(string $url): void
    {
        header('Location: ' . $url);
        exit();
    }

    protected function getIntParam(string $key, int $default = 0): int
    {
        return isset($_GET[$key]) ? max(0, (int) $_GET[$key]) : $default;
    }

    protected function getStringParam(string $key, string $default = ''): string
    {
        return trim($_GET[$key] ?? $default);
    }

    protected function getRequestBody(string $key, string $default = ''): string
    {
        return trim($_POST[$key] ?? $default);
    }
}
