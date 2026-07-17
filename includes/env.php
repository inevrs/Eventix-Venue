<?php
/**
 * Lightweight .env file loader for Eventix
 * Reads .env from project root and loads variables into the environment.
 * System environment variables always take priority over .env values.
 */
function loadEnv() {
    $envPath = dirname(__DIR__) . '/.env';
    if (!file_exists($envPath)) {
        return;
    }

    $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        // Skip comments
        if (empty($line) || strpos($line, '#') === 0) {
            continue;
        }

        if (strpos($line, '=') !== false) {
            list($key, $val) = explode('=', $line, 2);
            $key = trim($key);
            $val = trim($val);

            // Strip surrounding quotes
            if (preg_match('/^([\'"])(.*)\1$/', $val, $matches)) {
                $val = $matches[2];
            }

            // Only set if not already defined by system environment
            if (getenv($key) === false) {
                putenv("$key=$val");
                $_ENV[$key] = $val;
                $_SERVER[$key] = $val;
            }
        }
    }
}

loadEnv();
?>
