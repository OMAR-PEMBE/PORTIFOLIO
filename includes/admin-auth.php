<?php
declare(strict_types=1);

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/RateLimiter.php';

function startAdminSession(): void
{
    if (session_status() === PHP_SESSION_ACTIVE) {
        return;
    }
    ini_set('session.use_strict_mode', '1');
    ini_set('session.use_only_cookies', '1');
    session_name('portfolio_admin');
    $scriptDirectory = str_replace('\\', '/', dirname((string) ($_SERVER['SCRIPT_NAME'] ?? '/admin/index.php')));
    $cookiePath = rtrim($scriptDirectory, '/');
    if ($cookiePath === '' || $cookiePath === '.') {
        $cookiePath = '/admin';
    }
    session_set_cookie_params([
        'httponly' => true,
        'secure' => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
        'samesite' => 'Strict',
        'path' => $cookiePath,
    ]);
    session_start();
}

function adminCsrf(): string
{
    if (!isset($_SESSION['admin_csrf'])) {
        $_SESSION['admin_csrf'] = bin2hex(random_bytes(32));
    }
    return (string) $_SESSION['admin_csrf'];
}

function validAdminCsrf(): bool
{
    return isset($_POST['csrf'], $_SESSION['admin_csrf'])
        && hash_equals((string) $_SESSION['admin_csrf'], (string) $_POST['csrf']);
}

function requireAdmin(): void
{
    $authenticatedAt = (int) ($_SESSION['admin_authenticated_at'] ?? 0);
    if (empty($_SESSION['admin_authenticated']) || $authenticatedAt < time() - 7200) {
        if (session_status() === PHP_SESSION_ACTIVE) {
            destroyAdminSession();
        }
        header('Location: index.php');
        exit;
    }
    $_SESSION['admin_authenticated_at'] = time();
}

function destroyAdminSession(): void
{
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
    }
    session_destroy();
}

function adminLoginKey(string $username): string
{
    return 'admin:' . ($_SERVER['REMOTE_ADDR'] ?? 'unknown') . ':' . strtolower($username);
}

function recordFailedAdminLogin(string $username): bool
{
    return (new RateLimiter(sys_get_temp_dir(), environment('REDIS_DSN')))->consume(adminLoginKey($username), 5, 900) === 1;
}

function clearFailedAdminLogins(string $username): void
{
    (new RateLimiter(sys_get_temp_dir(), environment('REDIS_DSN')))->clear(adminLoginKey($username));
}
