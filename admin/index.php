<?php
declare(strict_types=1);

session_set_cookie_params([
    'httponly' => true,
    'secure' => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
    'samesite' => 'Lax',
]);
session_start();

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/Project.php';
require_once __DIR__ . '/../includes/project-store.php';
require_once __DIR__ . '/../includes/project-repository.php';
require_once __DIR__ . '/../includes/site-content-store.php';

function adminRedirect(): void
{
    header('Location: dashboard.php');
    exit;
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
    return isset($_POST['csrf'], $_SESSION['admin_csrf']) && hash_equals((string) $_SESSION['admin_csrf'], (string) $_POST['csrf']);
}

function adminInput(string $name): string
{
    return trim((string) ($_POST[$name] ?? ''));
}

$adminHash = environment('ADMIN_PASSWORD_HASH');
$adminUsername = environment('ADMIN_USERNAME', 'admin');
$error = null;
$message = null;

if (isset($_GET['logout'])) {
    $_SESSION = [];
    session_destroy();
    adminRedirect();
}

if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && isset($_POST['login'])) {
    $username = trim((string) ($_POST['username'] ?? ''));
    if ($adminHash !== null && hash_equals($adminUsername, $username) && password_verify((string) ($_POST['password'] ?? ''), $adminHash)) {
        session_regenerate_id(true);
        $_SESSION['admin_authenticated'] = true;
        adminRedirect();
    }
    $error = 'Invalid username or password.';
}

$authenticated = !empty($_SESSION['admin_authenticated']);
if ($authenticated && ($_SERVER['REQUEST_METHOD'] ?? '') === 'GET') {
    adminRedirect();
}
if ($authenticated && ($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && !isset($_POST['login'])) {
    if (!validAdminCsrf()) {
        $error = 'The form expired. Please try again.';
    } else {
        try {
            $store = new ProjectStore(dirname(__DIR__));
            $records = $store->records();
            $action = (string) ($_POST['action'] ?? 'save');
            if ($action === 'site-content') {
                $content = siteContent();
                foreach (array_keys($content) as $key) {
                    $content[$key] = adminInput($key);
                }
                saveSiteContent($content);
                $message = 'Site content saved.';
                goto admin_content_saved;
            }
            $slug = adminInput('slug');

            if (!preg_match('/^[a-z0-9]+(?:-[a-z0-9]+)*$/', $slug)) {
                throw new InvalidArgumentException('Use a lowercase slug with letters, numbers, and hyphens.');
            }
            if ($action === 'delete') {
                unset($records[$slug]);
                $store->save($records);
                $message = 'Project deleted.';
            } else {
                $gallery = [];
                foreach (preg_split('/\R/', adminInput('gallery')) ?: [] as $galleryLine) {
                    $galleryLine = trim($galleryLine);
                    if ($galleryLine === '') {
                        continue;
                    }
                    $parts = array_map('trim', explode('|', $galleryLine, 2));
                    if (count($parts) === 1) {
                        $gallery[] = $parts[0];
                        continue;
                    }
                    $gallery[] = ['type' => $parts[0], 'url' => $parts[1]];
                }
                $record = [
                    'title' => adminInput('title'),
                    'type' => adminInput('type'),
                    'file' => adminInput('file'),
                    'client' => adminInput('client'),
                    'service' => adminInput('service'),
                    'date' => adminInput('date'),
                    'location' => adminInput('location'),
                    'background' => adminInput('background'),
                    'challenges' => adminInput('challenges'),
                    'solution' => adminInput('solution'),
                    'gallery' => $gallery,
                ];
                Project::fromRecord($slug, $record);
                $records[$slug] = $record;
                $store->save($records);
                $message = 'Project saved.';
            }
        } catch (Throwable $exception) {
            $error = $exception->getMessage();
        }
    }
}
admin_content_saved:

if (!$authenticated) {
    ?><!doctype html>
<html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Admin login</title><link rel="stylesheet" href="admin.css?v=20260822"></head><body class="admin-page admin-login-page"><main class="admin-login-shell"><section class="admin-login-brand"><p class="admin-login-mark">OP</p><p class="admin-kicker">Private workspace</p><h1>Welcome back</h1><p>Manage your portfolio, projects, and website content in one place.</p></section><section class="admin-login-card"><p class="admin-kicker">Portfolio Admin</p><h2>Sign in</h2><?php if ($adminHash === null): ?><p class="admin-error">ADMIN_PASSWORD_HASH is not configured.</p><?php endif; ?><?php if ($error !== null): ?><p class="admin-error"><?= escapeHtml($error) ?></p><?php endif; ?><form method="post" class="admin-login-form"><label for="username">Username<input id="username" name="username" type="text" autocomplete="username" required autofocus></label><label for="password">Password<input id="password" name="password" type="password" autocomplete="current-password" required></label><button name="login" type="submit">Sign in <span aria-hidden="true">&rarr;</span></button></form></section></main></body></html><?php
    exit;
}

$store = new ProjectStore(dirname(__DIR__));
$records = $store->records();
$selectedSlug = (string) ($_GET['project'] ?? array_key_first($records) ?? '');
$selected = isset($records[$selectedSlug]) && is_array($records[$selectedSlug]) ? $records[$selectedSlug] : [];
$galleryLines = [];
foreach (is_array($selected['gallery'] ?? null) ? $selected['gallery'] : [] as $galleryItem) {
    $galleryLines[] = is_array($galleryItem) ? ($galleryItem['type'] . ' | ' . $galleryItem['url']) : $galleryItem;
}
$gallery = implode(PHP_EOL, $galleryLines);
$content = siteContent();
?><!doctype html><link rel="stylesheet" href="admin.css?v=20260822">
<html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Portfolio Admin</title><link rel="stylesheet" href="../style.css?v=20260822"></head><body class="admin-page"><main class="admin-shell"><header class="admin-header"><div><p class="admin-kicker">Content management</p><h1>Portfolio Admin</h1></div><a href="?logout=1">Sign out</a></header><?php if ($message !== null): ?><p class="admin-success"><?= escapeHtml($message) ?></p><?php endif; ?><?php if ($error !== null): ?><p class="admin-error"><?= escapeHtml($error) ?></p><?php endif; ?><section class="admin-layout"><aside><h2>Projects</h2><a class="admin-new" href="?project=new">+ New project</a><ul><?php foreach ($records as $slug => $record): ?><li><a class="<?= $slug === $selectedSlug ? 'active' : '' ?>" href="?project=<?= rawurlencode($slug) ?>"><?= escapeHtml($record['title'] ?? $slug) ?></a></li><?php endforeach; ?></ul></aside><form class="admin-form" method="post"><input type="hidden" name="csrf" value="<?= escapeHtml(adminCsrf()) ?>"><input type="hidden" name="action" value="save"><label>Slug<input name="slug" value="<?= escapeHtml($selectedSlug === 'new' ? '' : $selectedSlug) ?>" required pattern="[a-z0-9]+(?:-[a-z0-9]+)*"></label><label>Title<input name="title" value="<?= escapeHtml($selected['title'] ?? '') ?>" required></label><label>Type<select name="type"><option value="image" <?= ($selected['type'] ?? 'image') === 'image' ? 'selected' : '' ?>>Image</option><option value="video" <?= ($selected['type'] ?? '') === 'video' ? 'selected' : '' ?>>Video</option></select></label><label>Preview file<input name="file" value="<?= escapeHtml($selected['file'] ?? '') ?>" required></label><label>Client<input name="client" value="<?= escapeHtml($selected['client'] ?? '') ?>" required></label><label>Service<input name="service" value="<?= escapeHtml($selected['service'] ?? '') ?>" required></label><label>Date<input name="date" value="<?= escapeHtml($selected['date'] ?? '') ?>" required></label><label>Location<input name="location" value="<?= escapeHtml($selected['location'] ?? '') ?>" required></label><label>Background<textarea name="background" required><?= escapeHtml($selected['background'] ?? '') ?></textarea></label><label>Challenges<textarea name="challenges" required><?= escapeHtml($selected['challenges'] ?? '') ?></textarea></label><label>Solution<textarea name="solution" required><?= escapeHtml($selected['solution'] ?? '') ?></textarea></label><label>Gallery files <small>One asset path per line</small><textarea name="gallery" required><?= escapeHtml($gallery) ?></textarea><div class="admin-actions"><button type="submit">Save project</button><?php if ($selectedSlug !== 'new' && isset($records[$selectedSlug])): ?><button class="admin-delete" type="submit" name="action" value="delete" onclick="return confirm('Delete this project?');">Delete</button><?php endif; ?></div></form></section></main></body></html>
