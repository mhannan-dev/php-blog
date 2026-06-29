<?php

/**
 * Format — static utility helpers for formatting, sanitisation and output escaping.
 */
class Format
{
    /**
     * Format a date string for display.
     */
    public static function formatDate(string $date): string
    {
        return date('M j, Y g:i A', strtotime($date));
    }

    /**
     * Shorten a block of text to a word boundary within $limit characters.
     */
    public static function textShorten(string $text, int $limit = 200): string
    {
        if (mb_strlen($text) <= $limit) {
            return $text;
        }

        $text = mb_substr($text, 0, $limit + 1);
        $text = mb_substr($text, 0, (int) mb_strrpos($text, ' '));

        return $text . ' ...';
    }

    /**
     * Basic input sanitisation for user-submitted strings.
     * NOTE: this is NOT a substitute for prepared statements.
     * Use $db->escape() before interpolating into SQL.
     */
    public static function validation(string $data): string
    {
        return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
    }

    /**
     * Escape a value for safe HTML output.
     * Use this on every variable echoed into HTML.
     */
    public static function e(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }

    /**
     * Derive a human-readable page title from the current script filename.
     */
    public static function title(): string
    {
        $path  = $_SERVER['SCRIPT_FILENAME'];
        $title = basename($path, '.php');
        $title = str_replace('_', ' ', $title);

        if ($title === 'index') {
            $title = 'Home';
        }

        return ucwords($title);
    }

    /**
     * Generate a URL-friendly slug from string.
     */
    public static function slugify(string $text): string
    {
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        $text = preg_replace('~[^-\w]+~', '', $text);
        $text = trim($text, '-');
        $text = preg_replace('~-+~', '-', $text);
        return strtolower($text);
    }
}
