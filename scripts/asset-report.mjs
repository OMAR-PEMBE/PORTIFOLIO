import { readdir, stat } from 'node:fs/promises';
import path from 'node:path';

const root = path.join(process.cwd(), 'assets');
const rows = [];
async function walk(directory) {
  for (const entry of await readdir(directory, { withFileTypes: true })) {
    const absolute = path.join(directory, entry.name);
    if (entry.isDirectory()) await walk(absolute);
    else rows.push({ file: path.relative(process.cwd(), absolute), bytes: (await stat(absolute)).size });
  }
}
await walk(root);
rows.sort((a, b) => b.bytes - a.bytes);
const total = rows.reduce((sum, row) => sum + row.bytes, 0);
const pngs = rows.filter((row) => /\.png$/i.test(row.file));
const oversized = rows.filter((row) => row.bytes > 2 * 1048576);
console.log(`Assets: ${rows.length} files, ${(total / 1048576).toFixed(2)} MiB; PNGs: ${pngs.length}.`);
if (oversized.length > 0) console.log(`Optimization candidates (>2 MiB): ${oversized.length}.`);
console.log('Largest assets:');
for (const row of rows.slice(0, 15)) console.log(`${(row.bytes / 1048576).toFixed(2)} MiB\t${row.file}`);
