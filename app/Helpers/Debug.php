<?php

/**
 * Debug helpers — only loaded in development (APP_ENV=development).
 *
 * dump(...$vars)  — dump values, last PHP error, and request data together.
 */

if (!function_exists('dump')) {
    /**
     * Dump one or more values alongside the last PHP error and
     * current request data ($_GET, $_POST, $_SERVER subset).
     *
     * Usage:
     *   dump($result);
     *   dump($post, $category);
     *
     * @param mixed ...$vars
     */
    function dump(mixed ...$vars): void
    {
        // ── Caller location ───────────────────────────────────────────────
        $trace    = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 5);
        $caller   = null;
        foreach ($trace as $frame) {
            if (!isset($frame['file']) || realpath($frame['file']) === realpath(__FILE__)) {
                continue;
            }
            $caller = $frame;
            break;
        }
        $docRoot  = realpath($_SERVER['DOCUMENT_ROOT'] ?? '') ?: '';
        $file     = $caller ? str_replace($docRoot, '', realpath($caller['file'])) : 'unknown';
        $line     = $caller['line'] ?? '?';

        // ── Helpers ───────────────────────────────────────────────────────
        $esc  = fn(string $v): string => htmlspecialchars($v, ENT_QUOTES, 'UTF-8');
        $vd   = function (mixed $val) use ($esc): string {
            ob_start();
            var_dump($val);
            return $esc(ob_get_clean());
        };

        // ── Collect data ──────────────────────────────────────────────────
        $lastError = error_get_last();

        $serverKeys = [
            'REQUEST_METHOD', 'REQUEST_URI', 'QUERY_STRING',
            'HTTP_HOST', 'HTTP_REFERER', 'HTTP_USER_AGENT',
            'REMOTE_ADDR', 'SERVER_NAME', 'SERVER_PORT',
            'SCRIPT_FILENAME', 'PHP_SELF',
        ];
        $serverData = array_filter(
            array_intersect_key($_SERVER, array_flip($serverKeys)),
            fn($v) => $v !== ''
        );

        // ── CSS (once per page) ───────────────────────────────────────────
        static $cssWritten = false;
        if (!$cssWritten) {
            $cssWritten = true;
            echo <<<'CSS'
            <style>
                ._dd { font-family:"Fira Code","Cascadia Code","Consolas",monospace; font-size:12.5px; line-height:1.65; margin:14px 0; border-radius:6px; overflow:hidden; box-shadow:0 4px 18px rgba(0,0,0,.45); }
                ._dd-hdr { background:#181825; color:#cba6f7; padding:8px 16px; font-size:11px; font-weight:700; letter-spacing:.1em; text-transform:uppercase; display:flex; justify-content:space-between; }
                ._dd-file { color:#6c7086; font-weight:400; }
                ._dd-section { background:#1e1e2e; color:#cdd6f4; padding:12px 16px; border-top:1px solid #313244; white-space:pre-wrap; word-break:break-word; }
                ._dd-section-title { color:#89b4fa; font-size:11px; font-weight:700; letter-spacing:.08em; text-transform:uppercase; margin-bottom:6px; }
                ._dd-error  { background:#2a1a1a; border-top:1px solid #f38ba8; }
                ._dd-error ._dd-section-title { color:#f38ba8; }
                ._dd-get    { background:#1a1a2e; border-top:1px solid #a6e3a1; }
                ._dd-get ._dd-section-title { color:#a6e3a1; }
                ._dd-post   { background:#1a2a1e; border-top:1px solid #fab387; }
                ._dd-post ._dd-section-title { color:#fab387; }
                ._dd-server { background:#1e1a2a; border-top:1px solid #f9e2af; }
                ._dd-server ._dd-section-title { color:#f9e2af; }
            </style>
            CSS;
        }

        // ── Render ────────────────────────────────────────────────────────
        $varCount = count($vars);
        foreach ($vars as $i => $var) {
            $label = $varCount > 1 ? 'dump · var ' . ($i + 1) : 'dump';
            echo '<div class="_dd">';

            // Header
            echo '<div class="_dd-hdr">';
            echo '<span>' . $esc($label) . '</span>';
            echo '<span class="_dd-file">' . $esc($file . ':' . $line) . '</span>';
            echo '</div>';

            // Dumped value
            echo '<div class="_dd-section">';
            echo '<div class="_dd-section-title">value</div>';
            echo $vd($var);
            echo '</div>';

            // Last PHP error
            if ($lastError !== null) {
                $typeMap = [
                    E_ERROR => 'E_ERROR', E_WARNING => 'E_WARNING',
                    E_NOTICE => 'E_NOTICE', E_DEPRECATED => 'E_DEPRECATED',
                    E_USER_ERROR => 'E_USER_ERROR', E_USER_WARNING => 'E_USER_WARNING',
                ];
                $errType = $typeMap[$lastError['type']] ?? ('error type ' . $lastError['type']);
                echo '<div class="_dd-section _dd-error">';
                echo '<div class="_dd-section-title">last php error</div>';
                echo $esc("[{$errType}] {$lastError['message']}\n")
                   . $esc("in {$lastError['file']} on line {$lastError['line']}");
                echo '</div>';
            }

            // $_GET
            if (!empty($_GET)) {
                echo '<div class="_dd-section _dd-get">';
                echo '<div class="_dd-section-title">$_GET</div>';
                echo $vd($_GET);
                echo '</div>';
            }

            // $_POST
            if (!empty($_POST)) {
                echo '<div class="_dd-section _dd-post">';
                echo '<div class="_dd-section-title">$_POST</div>';
                echo $vd($_POST);
                echo '</div>';
            }

            // $_SERVER (filtered subset)
            if (!empty($serverData)) {
                echo '<div class="_dd-section _dd-server">';
                echo '<div class="_dd-section-title">$_SERVER</div>';
                echo $vd($serverData);
                echo '</div>';
            }

            echo '</div>'; // ._dd
        }
    }
}
