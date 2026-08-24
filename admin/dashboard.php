<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/admin-auth.php';
startAdminSession();
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/Project.php';
require_once __DIR__ . '/../includes/project-store.php';
require_once __DIR__ . '/../includes/project-repository.php';
require_once __DIR__ . '/../includes/site-content-store.php';

requireAdmin();

if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && ($_POST['action'] ?? '') === 'logout') {
    if (!validAdminCsrf()) {
        http_response_code(403);
        exit('Invalid logout request.');
    }
    destroyAdminSession();
    header('Location: index.php');
    exit;
}

function dashboardInput(string $name): string
{
    return trim((string) ($_POST[$name] ?? ''));
}

function dashboardGalleryUploads(string $slug): array
{
    $files = $_FILES['gallery_files'] ?? null;
    if (!is_array($files) || !isset($files['name'], $files['tmp_name'], $files['error'], $files['size'])) {
        return [];
    }

    $defaultDirectory = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'projects';
    $directory = environment('UPLOAD_STORAGE_DIR', $defaultDirectory) ?? $defaultDirectory;
    if (!is_dir($directory) && !mkdir($directory, 0755, true) && !is_dir($directory)) {
        throw new RuntimeException('Unable to create the project upload directory.');
    }

    $allowed = [
        'image/jpeg' => ['type' => 'image', 'extension' => 'jpg', 'limit' => 10 * 1024 * 1024],
        'image/png' => ['type' => 'image', 'extension' => 'png', 'limit' => 10 * 1024 * 1024],
        'image/webp' => ['type' => 'image', 'extension' => 'webp', 'limit' => 10 * 1024 * 1024],
        'image/gif' => ['type' => 'image', 'extension' => 'gif', 'limit' => 10 * 1024 * 1024],
        'video/mp4' => ['type' => 'video', 'extension' => 'mp4', 'limit' => 100 * 1024 * 1024],
        'video/webm' => ['type' => 'video', 'extension' => 'webm', 'limit' => 100 * 1024 * 1024],
        'video/quicktime' => ['type' => 'video', 'extension' => 'mov', 'limit' => 100 * 1024 * 1024],
    ];
    $uploaded = [];
    $count = is_array($files['name']) ? count($files['name']) : 0;
    if ($count > 20) {
        throw new InvalidArgumentException('Upload no more than 20 files at a time.');
    }
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    try {
    for ($index = 0; $index < $count; $index++) {
        if (($files['error'][$index] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
            continue;
        }
        if (($files['error'][$index] ?? UPLOAD_ERR_OK) !== UPLOAD_ERR_OK || !is_uploaded_file($files['tmp_name'][$index])) {
            throw new RuntimeException('One of the selected files could not be uploaded.');
        }
        $mime = $finfo->file($files['tmp_name'][$index]);
        if (!is_string($mime) || !isset($allowed[$mime]) || (int) $files['size'][$index] > $allowed[$mime]['limit']) {
            throw new InvalidArgumentException('Only JPG, PNG, WebP, GIF, MP4, WebM, or MOV files within the size limits are allowed.');
        }
        $filename = $slug . '-' . bin2hex(random_bytes(8)) . '.' . $allowed[$mime]['extension'];
        $target = $directory . DIRECTORY_SEPARATOR . $filename;
        if (!move_uploaded_file($files['tmp_name'][$index], $target)) {
            throw new RuntimeException('Unable to store an uploaded file.');
        }
        $uploaded[] = ['type' => $allowed[$mime]['type'], 'url' => 'assets/uploads/projects/' . $filename];
    }
    } catch (Throwable $exception) {
        deleteGalleryFiles($uploaded);
        throw $exception;
    }
    return $uploaded;
}

function localGalleryPath(mixed $item): ?string
{
    $url = is_array($item) ? (string) ($item['url'] ?? '') : (string) $item;
    if (!preg_match('#^assets/uploads/projects/[a-z0-9-]+\.(?:jpe?g|png|webp|gif|mp4|webm|mov)$#i', $url)) {
        return null;
    }
    $defaultDirectory = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'projects';
    $root = realpath(environment('UPLOAD_STORAGE_DIR', $defaultDirectory) ?? $defaultDirectory);
    $path = realpath(dirname(__DIR__) . '/' . $url);
    return $root !== false && $path !== false && str_starts_with($path, $root . DIRECTORY_SEPARATOR) ? $path : null;
}

function deleteGalleryFiles(array $items): void
{
    foreach ($items as $item) {
        $path = localGalleryPath($item);
        if ($path !== null && is_file($path) && !unlink($path)) {
            error_log((string) json_encode(['event' => 'admin.media_delete_failed', 'path' => $path]));
        }
    }
}

function galleryItemType(mixed $item): string
{
    return is_array($item) ? (string) ($item['type'] ?? 'media') : 'image';
}

function galleryItemUrl(mixed $item): string
{
    return is_array($item) ? (string) ($item['url'] ?? '') : (string) $item;
}

function galleryItemLabel(mixed $item): string
{
    $url = galleryItemUrl($item);
    $path = (string) parse_url($url, PHP_URL_PATH);
    return basename($path) !== '' && basename($path) !== '/' ? basename($path) : ((string) parse_url($url, PHP_URL_HOST) ?: $url);
}

function dashboardWebsiteLinks(): array
{
    $links = [];
    foreach (preg_split('/\R/', dashboardInput('gallery_websites')) ?: [] as $url) {
        $url = trim($url);
        if ($url === '') {
            continue;
        }
        if (!filter_var($url, FILTER_VALIDATE_URL) || !in_array(strtolower((string) parse_url($url, PHP_URL_SCHEME)), ['http', 'https'], true)) {
            throw new InvalidArgumentException('Enter valid http or https website URLs.');
        }
        $links[] = ['type' => 'website', 'url' => $url];
    }
    return $links;
}

$error = null;
$message = null;
$store = new ProjectStore(dirname(__DIR__));
$records = $store->records();
$selectedSlug = (string) ($_GET['project'] ?? array_key_first($records) ?? '');
$view = ($_GET['view'] ?? '') === 'site' ? 'site' : 'project';

if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    $newUploads = [];
    try {
        if (!isset($_POST['csrf'], $_SESSION['admin_csrf']) || !hash_equals((string) $_SESSION['admin_csrf'], (string) $_POST['csrf'])) {
            throw new RuntimeException('The form expired. Please try again.');
        }
        $action = (string) ($_POST['action'] ?? '');
        if ($action === 'logout') {
            throw new RuntimeException('Invalid logout request.');
        } elseif ($action === 'site-content') {
            $content = siteContent();
            foreach (array_keys($content) as $key) {
                $content[$key] = dashboardInput($key);
            }
            saveSiteContent($content);
            $view = 'site';
            $message = 'Site content saved.';
        } else {
            $slug = dashboardInput('slug');
            $originalSlug = dashboardInput('original_slug');
            if (!preg_match('/^[a-z0-9]+(?:-[a-z0-9]+)*$/', $slug)) {
                throw new InvalidArgumentException('Use a lowercase slug with letters, numbers, and hyphens.');
            }
            if ($action === 'delete') {
                $deletedGallery = is_array($records[$slug]['gallery'] ?? null) ? $records[$slug]['gallery'] : [];
                unset($records[$slug]);
                $store->save($records);
                deleteGalleryFiles($deletedGallery);
                $selectedSlug = (string) array_key_first($records);
                $message = 'Project deleted.';
            } else {
                if ($originalSlug !== '' && !isset($records[$originalSlug])) {
                    throw new RuntimeException('The project being edited no longer exists. Refresh and try again.');
                }
                if ($slug !== $originalSlug && isset($records[$slug])) {
                    throw new InvalidArgumentException('That project slug is already in use.');
                }
                $oldGallery = $originalSlug !== '' && is_array($records[$originalSlug]['gallery'] ?? null) ? $records[$originalSlug]['gallery'] : [];
                $record = [
                    'title' => dashboardInput('title'), 'type' => dashboardInput('type'), 'file' => dashboardInput('file'),
                    'client' => dashboardInput('client'), 'service' => dashboardInput('service'), 'date' => dashboardInput('date'),
                    'location' => dashboardInput('location'), 'background' => dashboardInput('background'),
                    'challenges' => dashboardInput('challenges'), 'solution' => dashboardInput('solution'), 'gallery' => [],
                ];
                foreach ((array) ($_POST['keep_gallery'] ?? []) as $encodedItem) {
                    $item = json_decode((string) $encodedItem, true);
                    if (is_string($item) || (is_array($item) && isset($item['type'], $item['url']))) {
                        $record['gallery'][] = $item;
                    }
                }
                $newUploads = dashboardGalleryUploads($slug);
                $record['gallery'] = array_merge($record['gallery'], $newUploads, dashboardWebsiteLinks());
                Project::fromRecord($slug, $record);
                if ($originalSlug !== '' && $originalSlug !== $slug) {
                    unset($records[$originalSlug]);
                }
                $records[$slug] = $record;
                $store->save($records);
                $keptPaths = array_filter(array_map('localGalleryPath', $record['gallery']));
                $removedItems = array_filter($oldGallery, static function (mixed $item) use ($keptPaths): bool {
                    $path = localGalleryPath($item);
                    return $path !== null && !in_array($path, $keptPaths, true);
                });
                deleteGalleryFiles($removedItems);
                $selectedSlug = $slug;
                $message = 'Project saved.';
            }
        }
    } catch (Throwable $exception) {
        deleteGalleryFiles($newUploads);
        $error = $exception->getMessage();
    }
}

$selected = isset($records[$selectedSlug]) && is_array($records[$selectedSlug]) ? $records[$selectedSlug] : [];
$galleryLines = [];
foreach (is_array($selected['gallery'] ?? null) ? $selected['gallery'] : [] as $item) {
    $galleryLines[] = $item;
}
$content = siteContent();
$csrf = (string) $_SESSION['admin_csrf'];
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Portfolio Admin</title>
    <link rel="stylesheet" href="admin.css?v=20260822">
</head>
<body class="admin-page">
    <main class="admin-shell">
        <header class="admin-header">
            <div>
                <p class="admin-kicker">Content management</p>
                <h1>Portfolio Admin</h1>
                <p>Manage every part of your website from one workspace.</p>
            </div>
            <form method="post" class="admin-logout-form"><input type="hidden" name="csrf" value="<?= escapeHtml($csrf) ?>"><button type="submit" name="action" value="logout">Sign out</button></form>
        </header>

        <?php if ($message !== null): ?><p class="admin-success admin-notice"><?= escapeHtml($message) ?></p><?php endif; ?>
        <?php if ($error !== null): ?><p class="admin-error admin-notice"><?= escapeHtml($error) ?></p><?php endif; ?>

        <div class="admin-workspace">
            <aside class="admin-sidebar">
                <p class="admin-sidebar-label">Workspace</p>
                <nav aria-label="Admin sections">
                    <a class="admin-nav-link <?= $view === 'site' ? 'active' : '' ?>" href="?view=site"><span class="admin-nav-icon">Aa</span><span>Site content</span></a>
                    <p class="admin-sidebar-label admin-project-label">Projects</p>
                    <a class="admin-nav-link admin-new" href="?project=new"><span class="admin-nav-icon">+</span><span>New project</span></a>
                    <?php foreach ($records as $slug => $record): ?>
                        <a class="admin-nav-link <?= $view === 'project' && $slug === $selectedSlug ? 'active' : '' ?>" href="?project=<?= rawurlencode($slug) ?>"><span class="admin-nav-icon admin-project-dot"></span><span><?= escapeHtml($record['title'] ?? $slug) ?></span></a>
                    <?php endforeach; ?>
                </nav>
            </aside>

            <section class="admin-editor">
                <?php if ($view === 'site'): ?>
                    <div class="admin-editor-heading"><div><p class="admin-kicker">Homepage content</p><h2>Edit site content</h2><p>Update the text visitors see across the homepage.</p></div></div>
                    <form method="post" class="admin-content-form">
                        <input type="hidden" name="csrf" value="<?= escapeHtml($csrf) ?>">
                        <input type="hidden" name="action" value="site-content">
                        <?php foreach ($content as $key => $value): ?><label><?= escapeHtml(ucwords(str_replace('_', ' ', $key))) ?><textarea name="<?= escapeHtml($key) ?>" required><?= escapeHtml($value) ?></textarea></label><?php endforeach; ?>
                        <button type="submit">Save site content</button>
                    </form>
                <?php else: ?>
                    <div class="admin-editor-heading"><div><p class="admin-kicker">Portfolio library</p><h2><?= $selectedSlug === 'new' ? 'Create project' : 'Edit project' ?></h2><p>Manage project details, media, and external links.</p></div></div>
                    <form class="admin-form" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="csrf" value="<?= escapeHtml($csrf) ?>">
                        <input type="hidden" name="action" value="save">
                        <input type="hidden" name="original_slug" value="<?= escapeHtml($selectedSlug === 'new' ? '' : $selectedSlug) ?>">
                        <label>Slug<input name="slug" value="<?= escapeHtml($selectedSlug === 'new' ? '' : $selectedSlug) ?>" required pattern="[a-z0-9]+(?:-[a-z0-9]+)*"></label>
                        <label>Title<input name="title" value="<?= escapeHtml($selected['title'] ?? '') ?>" required></label>
                        <label>Type<select name="type"><option value="image" <?= ($selected['type'] ?? 'image') === 'image' ? 'selected' : '' ?>>Image</option><option value="video" <?= ($selected['type'] ?? '') === 'video' ? 'selected' : '' ?>>Video</option></select></label>
                        <label>Preview URL or path<input name="file" value="<?= escapeHtml($selected['file'] ?? '') ?>" required></label>
                        <label>Client<input name="client" value="<?= escapeHtml($selected['client'] ?? '') ?>" required></label>
                        <label>Service<input name="service" value="<?= escapeHtml($selected['service'] ?? '') ?>" required></label>
                        <label>Date<input name="date" value="<?= escapeHtml($selected['date'] ?? '') ?>" required></label>
                        <label>Location<input name="location" value="<?= escapeHtml($selected['location'] ?? '') ?>" required></label>
                        <label class="admin-wide">Background<textarea name="background" required><?= escapeHtml($selected['background'] ?? '') ?></textarea></label>
                        <label class="admin-wide">Challenges<textarea name="challenges" required><?= escapeHtml($selected['challenges'] ?? '') ?></textarea></label>
                        <label class="admin-wide">Solution<textarea name="solution" required><?= escapeHtml($selected['solution'] ?? '') ?></textarea></label>
                        <fieldset class="admin-wide admin-media-fieldset">
                            <legend>Gallery media</legend>
                            <p class="admin-help">Upload images or videos directly from your computer. Existing media stays selected unless you untick it.</p>
                            <?php if ($galleryLines !== []): ?>
                                <div class="admin-existing-media">
                                    <?php foreach ($galleryLines as $galleryItem): ?>
                                        <?php $galleryJson = json_encode($galleryItem, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?>
                                        <label class="admin-media-item"><input type="checkbox" name="keep_gallery[]" value="<?= escapeHtml((string) $galleryJson) ?>" checked><span class="admin-media-badge admin-media-<?= escapeHtml(galleryItemType($galleryItem)) ?>"><?= escapeHtml(strtoupper(galleryItemType($galleryItem))) ?></span><span class="admin-media-name" title="<?= escapeHtml(galleryItemUrl($galleryItem)) ?>"><?= escapeHtml(galleryItemLabel($galleryItem)) ?></span></label>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                            <label>Upload image or video files<input class="admin-file-input" type="file" name="gallery_files[]" accept="image/jpeg,image/png,image/webp,image/gif,video/mp4,video/webm,video/quicktime" multiple></label>
                            <label>Website URLs <small>One valid http or https URL per line</small><textarea name="gallery_websites" placeholder="https://example.com"></textarea></label>
                        </fieldset>
                        <div class="admin-actions"><button type="submit">Save project</button><?php if ($selectedSlug !== 'new' && isset($records[$selectedSlug])): ?><button class="admin-delete" type="submit" name="action" value="delete" onclick="return confirm('Delete this project?');">Delete</button><?php endif; ?></div>
                    </form>
                <?php endif; ?>
            </section>
        </div>
    </main>
</body>
</html>
