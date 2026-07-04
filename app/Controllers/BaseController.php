<?php

namespace App\Controllers;

use Twig\Environment;
use Session;

abstract class BaseController
{
    protected Environment $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * Render a Twig template.
     *
     * @param string $template The template path (e.g., 'frontend/index.twig')
     * @param array $data Variables to pass to the template
     */
    protected function render(string $template, array $data = []): void
    {
        echo $this->twig->render($template, $data);
    }

    /**
     * Ensure the user is logged in. Redirects to login if not.
     */
    protected function requireLogin(): void
    {
        Session::checkSession();
    }

    /**
     * Ensure the user is an admin (role '0'). Redirects to dashboard if not.
     */
    protected function requireAdmin(): void
    {
        $this->requireLogin();
        if (Session::get('userRole') !== '0') {
            header('Location: index.php');
            exit();
        }
    }
}
