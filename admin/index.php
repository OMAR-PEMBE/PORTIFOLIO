<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/admin-auth.php';
require_once __DIR__ . '/../includes/project-repository.php';
startAdminSession();

if (!empty($_SESSION['admin_authenticated'])) {
    header('Location: dashboard.php');
    exit;
}

$adminHash = environment('ADMIN_PASSWORD_HASH');
$adminUsername = environment('ADMIN_USERNAME', 'admin') ?? 'admin';
$error = null;

if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    $username = trim((string) ($_POST['username'] ?? ''));
    $password = (string) ($_POST['password'] ?? '');
    if (!validAdminCsrf()) {
        $error = 'The form expired. Please try again.';
    } elseif ($adminHash !== null && hash_equals($adminUsername, $username) && password_verify($password, $adminHash)) {
        clearFailedAdminLogins($username);
        session_regenerate_id(true);
        $_SESSION['admin_authenticated'] = true;
        $_SESSION['admin_authenticated_at'] = time();
        header('Location: dashboard.php');
        exit;
    } else {
        if (!recordFailedAdminLogin($username)) {
            http_response_code(429);
            header('Retry-After: 900');
            $error = 'Too many sign-in attempts. Please wait 15 minutes.';
        } else {
            $error = 'Invalid username or password.';
        }
        error_log((string) json_encode(['event' => 'admin.login_failed', 'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown']));
    }
}
?>
<!doctype html>
<html lang="en">
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><meta name="robots" content="noindex,nofollow"><title>Admin login</title><link rel="stylesheet" href="admin.css?v=20260823"></head>
<body class="admin-page admin-login-page"><main class="admin-login-shell"><section class="admin-login-brand"><p class="admin-login-mark">OP</p><p class="admin-kicker">Private workspace</p><h1>Welcome back</h1><p>Manage your portfolio, projects, and website content in one place.</p></section><section class="admin-login-card"><p class="admin-kicker">Portfolio Admin</p><h2>Sign in</h2><?php if ($adminHash === null): ?><p class="admin-error">ADMIN_PASSWORD_HASH is not configured.</p><?php endif; ?><?php if ($error !== null): ?><p class="admin-error" role="alert"><?= escapeHtml($error) ?></p><?php endif; ?><form method="post" class="admin-login-form"><input type="hidden" name="csrf" value="<?= escapeHtml(adminCsrf()) ?>"><label for="username">Username<input id="username" name="username" type="text" autocomplete="username" required autofocus></label><label for="password">Password<input id="password" name="password" type="password" autocomplete="current-password" required></label><button type="submit">Sign in <span aria-hidden="true">&rarr;</span></button></form></section></main></body>
</html>
