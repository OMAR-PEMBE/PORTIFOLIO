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
console.log(`Assets: ${rows.length} files, ${(total / 1048576).toFixed(2)} MiB; PNGs: ${pngs.length}.`);
console.log('Largest assets:');
for (const row of rows.slice(0, 15)) console.log(`${(row.bytes / 1048576).toFixed(2)} MiB\t${row.file}`);
