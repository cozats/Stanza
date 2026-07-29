#!/usr/bin/env node
/**
 * One-shot importer from a live Stanza PHP install to Astro content collections.
 *
 * Usage:
 *   node scripts/migrate-php-content.mjs [--source /path/to/php-stanza] [--dry-run]
 *
 * Reads:
 *   <source>/config.json
 *   <source>/collections/*/collection.json
 *   <source>/collections/*/poems/*.{md,txt}
 *
 * Writes:
 *   src/content/collections/<slug>.json
 *   src/content/poems/<slug>-<filename>.md   (with frontmatter injected)
 *
 * Idempotent: re-running over the same source re-generates without data loss.
 */

import { readFileSync, writeFileSync, mkdirSync, readdirSync, statSync, existsSync } from 'fs';
import { resolve, join, basename, extname } from 'path';

// ── Args ────────────────────────────────────────────────────────────────────
const args = process.argv.slice(2);
const dryRun = args.includes('--dry-run');
const sourceIdx = args.indexOf('--source');
const phpRoot = sourceIdx >= 0 ? resolve(args[sourceIdx + 1]) : resolve('../../');
const contentDir = resolve(import.meta.dirname ?? '.', '../src/content');

console.log(`Source: ${phpRoot}`);
console.log(`Target: ${contentDir}`);
if (dryRun) console.log('DRY RUN — no files will be written');

// ── Helpers ──────────────────────────────────────────────────────────────────
function write(path, content) {
  if (dryRun) {
    console.log(`  [dry] write ${path}`);
    return;
  }
  mkdirSync(resolve(path, '..'), { recursive: true });
  writeFileSync(path, content, 'utf8');
  console.log(`  write ${path}`);
}

function slugify(text) {
  return text
    .toLowerCase()
    .replace(/[^\w\s-]/g, '')
    .replace(/[\s_]+/g, '-')
    .replace(/-+/g, '-')
    .trim()
    .replace(/^-|-$/g, '');
}

function getPoemTitle(content) {
  const match = content.match(/^#\s+(.+)$/m);
  return match ? match[1].trim() : null;
}

function detectLang(filename) {
  if (/_en\.(md|txt)$/i.test(filename)) return 'en';
  if (/_el\.(md|txt)$/i.test(filename)) return 'el';
  return 'en';
}

function getTranslationKey(filename) {
  const base = basename(filename, extname(filename));
  return base.replace(/_?(en|el)$/, '');
}

function stripFrontmatter(content) {
  if (!content.startsWith('---')) return content;
  const end = content.indexOf('---', 3);
  return end >= 0 ? content.slice(end + 3).trim() : content;
}

// ── Config ───────────────────────────────────────────────────────────────────
const configPath = join(phpRoot, 'config.json');
if (!existsSync(configPath)) {
  console.error(`config.json not found at ${configPath}`);
  console.error('Run with --source /path/to/your/php-stanza-install');
  process.exit(1);
}

const config = JSON.parse(readFileSync(configPath, 'utf8'));
console.log(`\nAuthor: ${config.author_name}`);

// ── Collections ───────────────────────────────────────────────────────────────
const collectionsDir = join(phpRoot, 'collections');
const dirs = readdirSync(collectionsDir).filter((d) => {
  try {
    return statSync(join(collectionsDir, d)).isDirectory();
  } catch {
    return false;
  }
});

let poemCount = 0;

for (const slug of dirs) {
  const collDir = join(collectionsDir, slug);
  const metaPath = join(collDir, 'collection.json');
  if (!existsSync(metaPath)) continue;

  const meta = JSON.parse(readFileSync(metaPath, 'utf8'));
  console.log(`\nCollection: ${slug} (${meta.title})`);

  // Write collection data file
  write(
    join(contentDir, 'collections', `${slug}.json`),
    JSON.stringify({ title: meta.title, author: meta.author || config.author_name }, null, 2),
  );

  // Poems
  const poemsDir = join(collDir, 'poems');
  if (!existsSync(poemsDir)) continue;

  const poemFiles = readdirSync(poemsDir).filter((f) => /\.(md|txt)$/i.test(f));
  for (const poemFile of poemFiles) {
    const poemPath = join(poemsDir, poemFile);
    const rawContent = readFileSync(poemPath, 'utf8');
    const body = stripFrontmatter(rawContent);
    const title = getPoemTitle(body) || basename(poemFile, extname(poemFile)).replace(/[_-]/g, ' ');
    const lang = detectLang(poemFile);
    const translationKey = getTranslationKey(poemFile);
    const mtime = statSync(poemPath).mtime;
    const date = mtime.toISOString().slice(0, 10);
    const outSlug = `${slug}-${basename(poemFile, extname(poemFile))}`;

    const frontmatter = [
      '---',
      `title: "${title.replace(/"/g, '\\"')}"`,
      `collection: ${slug}`,
      `lang: ${lang}`,
      `translationKey: ${translationKey}`,
      `date: ${date}`,
      '---',
      '',
    ].join('\n');

    write(join(contentDir, 'poems', `${outSlug}.md`), frontmatter + body);
    poemCount++;
  }
}

console.log(`\nDone. ${dirs.length} collection(s), ${poemCount} poem(s).`);
