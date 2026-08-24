import assert from 'node:assert/strict';
import { access, readFile } from 'node:fs/promises';
import path from 'node:path';
import { spawnSync } from 'node:child_process';

const root = process.cwd();
const php = process.env.PHP_BINARY || (process.platform === 'win32' ? 'C:\\xampp\\php\\php.exe' : 'php');
const phpFiles = ['index.php', 'projects.php', 'project-details.php', '404.php', 'health.php', 'admin/index.php', 'admin/dashboard.php', 'data/projects.php', 'data/site-content.php', 'includes/config.php', 'includes/admin-auth.php', 'includes/Project.php', 'includes/project-store.php', 'includes/project-repository.php', 'includes/site-content-store.php', 'includes/render-project-card.php', 'includes/RateLimiter.php', 'includes/ContactQueue.php', 'scripts/process-contact-queue.php', 'assets/mail/contact.php', 'assets/mail/ResendMailer.php'];

for (const file of phpFiles) {
  const lint = spawnSync(php, ['-l', file], { cwd: root, encoding: 'utf8' });
  assert.equal(lint.status, 0, `${file} failed PHP lint:\n${lint.stdout}${lint.stderr}`);
}

const dataSource = await readFile(path.join(root, 'data/projects.php'), 'utf8');
const slugs = [...dataSource.matchAll(/^\s{4}'([^']+)'\s*=>\s*\[/gm)].map((match) => match[1]);
assert.equal(slugs.length, 6, 'expected six distinct project records');
assert.equal(new Set(slugs).size, slugs.length, 'project slugs must be unique');

const sources = await Promise.all(['index.php', 'projects.php', 'project-details.php', '404.php', 'includes/page-scripts.php'].map((file) => readFile(path.join(root, file), 'utf8')));
const markup = sources.join('\n');
const themeScript = await readFile(path.join(root, 'assets/js/features/theme.js'), 'utf8');
assert.match(themeScript, /localStorage/);
assert.match(themeScript, /classList\.toggle\('bg-dark'/);
assert.doesNotMatch(markup, /Info@yourdomain\.com|\bmail\s*\(|Main Project - Title|themeforest\.validthemes|Image Not Found/i);
assert.doesNotMatch(markup, /href=["']#["']|services-details\.(?:php|html)|service\.html|projects\.html|resume\.html|pricing\.html|contact\.html|blog-with-sidebar\.html/i);

const home = sources[0];
assert.match(sources[3], /<title>Page Not Found \| Omar S Pembe<\/title>/);
assert.match(sources[3], /name="robots" content="noindex,follow"/);
for (const target of ['home', 'services', 'portfolio', 'resume', 'contact']) {
  assert.match(home, new RegExp(`id=["']${target}["']`), `missing #${target} navigation target`);
}

const handler = await readFile(path.join(root, 'assets/mail/contact.php'), 'utf8');
assert.match(handler, /configuredEmail\('CONTACT_RECIPIENT'\)/);
assert.match(handler, /consumeRateLimit/);
assert.doesNotMatch(handler, /\bmail\s*\(/);

const dashboard = await readFile(path.join(root, 'admin/dashboard.php'), 'utf8');
const login = await readFile(path.join(root, 'admin/index.php'), 'utf8');
assert.match(dashboard, /multipart\/form-data/);
assert.match(dashboard, /name="gallery_files\[\]"/);
assert.match(dashboard, /name="gallery_websites"/);
assert.doesNotMatch(dashboard, /name="gallery" required/);
assert.match(dashboard, /name="original_slug"/);
assert.match(dashboard, /name="action" value="logout"/);
assert.doesNotMatch(dashboard, /\?logout=1/);
assert.match(login, /recordFailedAdminLogin/);
assert.match(login, /name="csrf"/);
const adminAuth = await readFile(path.join(root, 'includes/admin-auth.php'), 'utf8');
assert.match(adminAuth, /SCRIPT_NAME/);
assert.doesNotMatch(adminAuth, /'path'\s*=>\s*'\/admin'/);
assert.match(sources[2], /http_response_code\(404\)/);

await access(path.join(root, '.htaccess'));
await access(path.join(root, 'assets/uploads/projects/.htaccess'));
for (const deploymentFile of ['Dockerfile', '.dockerignore', 'render.yaml', 'docker/apache.conf', 'docker/php.ini', 'health.php']) {
  await access(path.join(root, deploymentFile));
}
const apacheConfig = await readFile(path.join(root, '.htaccess'), 'utf8');
assert.match(apacheConfig, /Header always unset X-Powered-By/);
const renderBlueprint = await readFile(path.join(root, 'render.yaml'), 'utf8');
assert.match(renderBlueprint, /runtime: docker/);
assert.match(renderBlueprint, /healthCheckPath: \/health\.php/);
assert.doesNotMatch(renderBlueprint, /RESEND_API_KEY:\s+re_/);

for (const match of markup.matchAll(/(?:src|href|['"])(assets\/[^"'?#]+)["']/g)) {
  await access(path.join(root, match[1]));
}

console.log(`Regression checks passed for ${phpFiles.length} PHP files and ${slugs.length} project routes.`);
