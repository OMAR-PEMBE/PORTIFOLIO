import assert from 'node:assert/strict';
import { access, readFile } from 'node:fs/promises';
import path from 'node:path';
import { spawnSync } from 'node:child_process';

const root = process.cwd();
const php = process.env.PHP_BINARY || 'C:\\xampp\\php\\php.exe';
const phpFiles = ['index.php', 'projects.php', 'project-details.php', '404.php', 'data/projects.php', 'includes/render-project-card.php', 'assets/mail/contact.php', 'assets/mail/ResendMailer.php'];

for (const file of phpFiles) {
  const lint = spawnSync(php, ['-l', file], { cwd: root, encoding: 'utf8' });
  assert.equal(lint.status, 0, `${file} failed PHP lint:\n${lint.stdout}${lint.stderr}`);
}

const dataSource = await readFile(path.join(root, 'data/projects.php'), 'utf8');
const slugs = [...dataSource.matchAll(/^\s{4}'([^']+)'\s*=>\s*\[/gm)].map((match) => match[1]);
assert.equal(slugs.length, 6, 'expected six distinct project records');
assert.equal(new Set(slugs).size, slugs.length, 'project slugs must be unique');

const sources = await Promise.all(['index.php', 'projects.php', 'project-details.php', '404.php'].map((file) => readFile(path.join(root, file), 'utf8')));
const markup = sources.join('\n');
assert.doesNotMatch(markup, /Info@yourdomain\.com|\bmail\s*\(|Main Project - Title|themeforest\.validthemes|Image Not Found/i);
assert.doesNotMatch(markup, /href=["']#["']|services-details\.(?:php|html)|service\.html|projects\.html|resume\.html|pricing\.html|contact\.html|blog-with-sidebar\.html/i);

const home = sources[0];
for (const target of ['home', 'services', 'portfolio', 'resume', 'contact']) {
  assert.match(home, new RegExp(`id=["']${target}["']`), `missing #${target} navigation target`);
}

const handler = await readFile(path.join(root, 'assets/mail/contact.php'), 'utf8');
assert.match(handler, /configuredEmail\('CONTACT_RECIPIENT'\)/);
assert.match(handler, /consumeRateLimit/);
assert.doesNotMatch(handler, /\bmail\s*\(/);

for (const match of markup.matchAll(/(?:src|href)=["'](assets\/[^"'?#]+)["']/g)) {
  await access(path.join(root, match[1]));
}

console.log(`Regression checks passed for ${phpFiles.length} PHP files and ${slugs.length} project routes.`);
