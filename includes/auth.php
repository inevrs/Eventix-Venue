<?php
function startSecureSession() {
    if (session_status() === PHP_SESSION_NONE) {
        $cookieParams = session_get_cookie_params();
        $isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
            || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && strtolower($_SERVER['HTTP_X_FORWARDED_PROTO']) === 'https')
            || (isset($_SERVER['SERVER_PORT']) && (int)$_SERVER['SERVER_PORT'] === 443);

        session_set_cookie_params([
            'lifetime' => 0,
            'path' => $cookieParams['path'] ?? '/',
            'domain' => $cookieParams['domain'] ?? '',
            'secure' => $isHttps,
            'httponly' => true,
            'samesite' => 'Lax',
        ]);

        session_start();

        if (!isset($_SESSION['initiated'])) {
            session_regenerate_id(true);
            $_SESSION['initiated'] = true;
        }
    }
}

function requireLogin($role = null) {
    if (!isset($_SESSION['user_id'])) {
        header("Location: /eventix/login.php");
        exit();
    }
    if ($role && $_SESSION['role'] !== $role) {
        header("Location: /eventix/login.php");
        exit();
    }
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function userRole() {
    return $_SESSION['role'] ?? null;
}

function userName() {
    return $_SESSION['name'] ?? 'User';
}

function userInitial() {
    return strtoupper(substr($_SESSION['name'] ?? 'U', 0, 1));
}
?>
